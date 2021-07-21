<div class="search">
    <form action="search.php" method="get">
        <div class="center ">
            <div class="option">
                <label>
                    <input type="radio" name="searchby" id="title" value="title" checked>
                    Title
                </label>
                <label>
                    <input type="radio" name="searchby" id="author" value="author">
                    Author
                </label>
                <label>
                    <input type="radio" name="searchby" id="isbn" value="isbn">
                    ISBN
                </label>
            </div>
        </div>
        <input type="search" name="search" id="searchbox" placeholder="Search Book by Title, Author or ISBN" required>
        <input type="submit" value="Search">
    </form>
</div>