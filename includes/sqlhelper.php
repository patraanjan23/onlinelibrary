<?php
require('components/_nodirectaccess.php');
require_once('includes/sanitizer.php');

// only call after checking current session email is not being deleted
function deleteUser($conn, $email)
{
    $status = '';
    $sql = "DELETE FROM users WHERE email LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $conn->quote('%'.$email.'%'));
    $stmt->execute();
    $result = $stmt->get_result();     
    if ($result) {
        $status = "user $email deleted";
    } else {
        $status = "failed to delete $email: user has borrow";
    }
    return $status;
}

function userExists($conn, $email)
{
    $sql = "SELECT email FROM users WHERE email LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $conn->quote('%'.$email.'%'));
    $stmt->execute();
    $result = $stmt->get_result();     
    return ($result->num_rows > 0);
}

function retrieveEmail($postEmail)
{
    $emailObject = array();
    if (empty($postEmail)) {
        $emailObject['emailError'] = 'email empty';
        $emailObject['email'] = null;
    } else {
        $emailObject['email'] = sanitize_input($postEmail);
        if (!filter_var($emailObject['email'], FILTER_VALIDATE_EMAIL)) {
            $emailObject['emailError'] = 'invalid email format';
            $emailObject['email'] = null;
        }
    }
    return $emailObject;
}

function generate_password($len = 8)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
    $password = substr(str_shuffle($chars), 0, $len);
    return $password;
}

function createUser($conn, $email)
{
    $password = generate_password();
    $passhash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO `users` (`password`, `email`) VALUES(?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $passhash, $email);
    $stmt->execute();
    $result = $stmt->get_result();     
    if (!$result) {
        $password = null;
    }
    return $password;
}

function retrieveBook($postTitle, $postAuthor, $postISBN)
{
    if (empty($postTitle) || empty($postAuthor) || empty($postISBN)) {
        return null;
    }
    $book = array();
    $book['title'] = sanitize_input($postTitle);
    $book['author'] = sanitize_input($postAuthor);
    $book['isbn'] = sanitize_input($postISBN);

    // uncomment to enable ISBN check
    // if (strlen($book['isbn']) !== 10 || strlen($book['isbn']) !== 13) {
    //     return null;
    // }

    return $book;
}

function addBook($conn, $title, $author, $isbn)
{
    $stmt = $conn->prepare("INSERT INTO books (title, author, isbn) VALUES(?, ?, ?)");
    $stmt->bind_param("sss", $title, $author, $isbn);
    $title = $title;
    $isbn = $isbn;
    $author = $author;
    $stmt->execute();
    $result = $stmt->error === "" ? $isbn : null;
    $stmt->close();
    return $result;
}

function retrieveBookId($postBookId)
{
    if (empty($postBookId)) {
        return null;
    }
    $bookid = sanitize_input($postBookId);
    return $bookid;
}

function delBook($conn, $bookid)
{
    $stmt = $conn->prepare("DELETE FROM books WHERE book_id = ?");
    $stmt->bind_param('i', $bookid);
    $bookid = $bookid;
    $stmt->execute();
    $result = $stmt->error === "" ? "deleted book $bookid successfully" : "could not delete book $bookid, may be borrowed";
    $stmt->close();
    return $result;
}

function isBookAvailable($conn, $bookid)
{
    $val = false;
    $sql = "SELECT `b`.`book_id` `book_id`, `borrow_date`, `return_date`, `due_date` FROM `books` `b` LEFT JOIN `borrows` `bw` ON `b`.`book_id` = `bw`.`book_id` WHERE `b`.`book_id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $bookid);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (empty($row['borrow_date'])) {
                $val = true;
            } elseif (!empty($row['return_date'])) {
                $val = true;
            }
        }
    }
    $stmt->close();
    return $val;
}

function assignBook($conn, $userid, $bookid, $bordate, $duedate)
{
    $val = '';
    $sql = "INSERT INTO borrows 
            (book_id, user_id, borrow_date, due_date, return_date) 
            values(?, ?, ?, ?, NULL) 
            ON DUPLICATE KEY 
            UPDATE borrow_date=?, due_date=?, return_date=NULL";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iissss', $bookid, $userid, $bordate, $duedate, $bordate, $duedate);
    if ($stmt->execute()) {
        $val = "book $bookid assigned to user $userid on $bordate, due date $duedate";
    } else {
        $val = "book assignment failed";
    }
    $stmt->close();
    return $val;
}

function hasBorrowed($conn, $userid, $bookid)
{
    $val = false;
    $sql = "SELECT * FROM borrows WHERE user_id=? AND book_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userid, $bookid);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (!empty($row['borrow_date']) && empty($row['return_date'])) {
                $val = true;
            }
        }
    }
    $stmt->close();
    return $val;
}

function returnBook($conn, $userid, $bookid, $retdate)
{
    $val = '';
    $sql = "UPDATE borrows SET return_date = ? WHERE user_id = ? AND book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sii', $retdate, $userid, $bookid);
    if ($stmt->execute()) {
        // calculate fine if they have
        $fine = calcFine($conn, $userid, $bookid);

        $val = "book $bookid returned." . ($fine == 0 ? "" : " fine: Rs. $fine");
    } else {
        $val = "could not return the book";
    }
    $stmt->close();
    return $val;
}

// don't call explicitly
function calcFine($conn, $userid, $bookid)
{
    $fine = 0;

    // check if fine is needed
    $fineRequired = false;
    $duedate = $retdate = '';
    $sql = "SELECT due_date, return_date FROM borrows WHERE user_id = ? AND book_id = ?";
    $stmt1 = $conn->prepare($sql);
    $stmt1->bind_param('ii', $userid, $bookid);
    if ($stmt1->execute()) {
        $result = $stmt1->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $duedate = $row['due_date'];
            $retdate = $row['return_date'];
            $fineRequired = $retdate > $duedate;
        }
    }
    if ($fineRequired) {
        // calculate and update fine in fines table
        $days = round((strtotime($retdate) - strtotime($duedate)) / (60 * 60 * 24));
        $fine = 2.0 * $days;

        $sql = "INSERT INTO fines (user_id, due) VALUES (?, ?)
                ON DUPLICATE KEY
                UPDATE due = due + ?";
        $stmt2 = $conn->prepare($sql);
        $stmt2->bind_param("idd", $userid, $fine, $fine);
        $stmt2->execute();
    }
    return $fine;
}

function checkFine($conn, $userid)
{
    $sql = "SELECT * FROM fines WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userid);
    $val = '';
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $val = "fine for user $userid:" . ' due: Rs. ' . $row['due'] . ', paid: Rs. ' . (empty($row['paid']) ? '0' : $row['paid']);
        } else {
            $val = "no fine found for user $userid";
        }
    }
    $stmt->close();
    return $val;
}

function adjustFine($conn, $userid, $fine)
{
    $val = '';
    $sql = "SELECT * FROM fines WHERE user_id = ?";
    $stmt1 = $conn->prepare($sql);
    $stmt1->bind_param('i', $userid);
    if ($stmt1->execute()) {
        $result = $stmt1->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $due = $row['due'];
            $paid = $row['paid'];
            // don't overpay, the case is not handled
            if ($due != 0) {
                $sql = "UPDATE fines SET due = due - ?, paid = " . (empty($paid) ? '' : " paid + ") . " ? WHERE user_id = ?";
                $stmt2 = $conn->prepare($sql);
                $stmt2->bind_param('ddi', $fine, $fine, $userid);
                if ($stmt2->execute()) {
                    $val = "fine adjusted for user $userid";
                } else {
                    $val = "fine could not be adjusted for user $userid";
                }
                $stmt2->close();
            } else {
                $val = "user $userid has no fine";
            }
        } else {
            $val = "user $userid has no fine";
        }
    } else {
        $val = "error executing sql";
    }
    $stmt1->close();
    return $val;
}
