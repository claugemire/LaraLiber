<template>
<div>

    <div class="container flex" v-show="!bookDetail.show">

        <div class="w-1/2 border-r-2 pr-5">
            <h2 class="text-3xl">My Book List</h2>
            <button @click="saveOrder" class="btn btn-warning text-3xl" v-show="showSaveOrder">Save Your Sort Order</button>
            <div>
                <div class="flex flex-row border-b-2 my-3">
                    <button title="Sort By Title" @click="sortBooks('title')" class="py-2 text-center flex-1 rounded-t-lg text-l" :class="{ 'alert-secondary': sortBy == 'title' }">Title <span class="text-sm" v-if="sortBy == 'title'"><span v-if="sortDir =='asc'">&#9660;</span><span v-else>&#9650;</span></span></button>
                    <button title="Sort By Author" @click="sortBooks('author')" class="py-2 text-center flex-1 rounded-t-lg text-l" :class="{ 'alert-secondary': sortBy == 'author' }">Author <span class="text-sm" v-if="sortBy == 'author'"><span v-if="sortDir =='asc'">&#9660;</span><span v-else>&#9650;</span></span></button>
                    <button title="Sort By Read Status" @click="sortBooks('read')" class="py-2 text-center flex-1 rounded-t-lg text-l" :class="{ 'alert-secondary': sortBy == 'read' }">Status <span class="text-sm" v-if="sortBy == 'read'"><span v-if="sortDir =='asc'">&#9660;</span><span v-else>&#9650;</span></span></button>
                    <button title="Sort By My Order" @click="sortBooks('order')" class="py-2 text-center flex-1 rounded-t-lg text-l" :class="{ 'alert-secondary': sortBy == 'order' }">My Sort Order <span class="text-sm" v-if="sortBy == 'order'"><span v-if="sortDir =='asc'">&#9660;</span><span v-else>&#9650;</span></span></button>
                </div>
            </div>

            <div class="w-11/12 rounded-md mb-3" v-for="(book, index) in books" :key="index">
                <div class="flex p-3">
                    <img class="w-24 mr-2" :src="book.thumbnail" />
                    <div class="flex flex-col w-full">
                        <div class="self-start h-full">
                            <h2 class="text-xl">{{ book.title }}</h2>
                            By: {{ book.author }}
                            <div class="alert alert-success p-2 text-sm" v-if="book.read == true">You've read this book, Congrats!!</div>
                        </div>
                        <div class="self-start">
                            <button title="Get more info about this book" @click="populateBookDetail(book, false)" class="btn btn-info btn-sm">&#9728; Info</button>
                            <button title="Mark this book as read" @click="updateReadStatus(book)" class="btn btn-success btn-sm" v-if="book.read == false">&#9745; Mark as Read</button>
                            <button title="Mark this book as unread" @click="updateReadStatus(book)" class="btn btn-warning btn-sm" v-if="book.read == true">&#9745; Mark as Unread</button>
                            <button title="Remove this book from your list" @click="deleteBook(book)" class="btn btn-danger btn-sm">&#9747;</button>
                        </div>
                    </div>
                    <div class="w-5 self-end" v-if="sortBy == 'order'">
                        <button title="Move Book Up" @click="moveItemUp(book)" class="btn btn-info btn-sm rounded-b-0 border-b-2">&#8593;</button>
                        <button title="Move Book Down" @click="moveItemDown(book)" class="btn btn-info btn-sm rounded-t-0">&#8595;</button>
                    </div>

                </div>
            </div>

        </div>

        <div class="w-1/2 pl-4 pt-3 alert-secondary">
            <form v-on:submit.prevent="searchBooks">
                <input class="pl-3 rounded-l-lg rounded-r-none text-4xl bg-grey-200 w-4/5 mr-0" type="text" name="searchTerm" v-model="searchTerm" placeholder="Search for a Book" />
                <button @click="searchBooks" class="btn btn-info text-2xl px-3 py-2 ml-0 mb-3 rounded-r-lg rounded-l-none">&#9906;</button>
            </form>
            <div id="search-results">
                <hr class="w-11/12 mb-3" />

                <div class="w-11/12 bg-white rounded-md mb-3" v-for="result in searchResults" :key="result.id">
                    <div class="flex p-3">
                        <img class="mr-2" :src="result.volumeInfo.imageLinks.thumbnail" />
                        <div class="flex flex-col">
                            <div class="self-start h-full">
                                <h2 class="text-xl">{{ result.volumeInfo.title }}</h2>
                                By: {{ getAuthor(result.volumeInfo.authors) }}
                            </div>
                            <div class="self-start">
                                <button @click="addBook(result)" class="btn btn-success btn-sm">&#9733; Add To My List</button>
                                <button @click="populateBookDetail(result, true)" class="btn btn-info btn-sm">&#9728; More Info</button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <div id="details" class="details alert-dark p-24 absolute-center w-full h-full" v-show="bookDetail.show">
        <button class="p-3 text-2xl btn-secondary text-right w-full" @click="bookDetail.show = false">X</button>

        <div class="w-full bg-white rounded-md mb-3">
            <div class="flex p-3">
                <img class="mr-3 h-48" :src="bookDetail.thumbnail" />
                <div class="flex flex-col">
                    <div class="self-start h-full text-xl">
                        <h2 class="text-6xl">{{ bookDetail.title }}</h2>
                        <p>By: <b>{{ bookDetail.author }}</b></p>
                        <p>ISBN: {{ bookDetail.isbn }} | {{ bookDetail.page_count }} pages</p>

                        <p>
                            {{ bookDetail.description }}
                        </p>
                        <button @click="addBook(bookDetail, false); bookDetail.show = false" class="btn btn-success btn-sm" v-show="bookDetail.addToList">&#9733; Add To My List</button>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
</template>

<script>
export default {
    data() {
        return {
            books: null,
            currentSort: 'title',
            currentSortDir: 'asc',
            showSaveOrder: false,
            user: null,
            searchTerm: null,
            searchResults: null,
            sortBy: 'order',
            sortDir: 'asc',
            bookOrder: null,
            bookDetail: {
                show: false,
                title: '',
                thumbnail: '',
                description: '',
                author: '',
                isbn: '',
                page_count: '',
                addToList: false,
            },
            newBook: {
                user_id: this.user_id,
                title: '',
                thumbnail: '',
                description: '',
                author: '',
                isbn: '',
                page_count: ''

            },
        }
    },
    props: [
        'user_id',
        'api_secret',
        'gb_key'
    ],
    methods: {
        searchBooks() {
            axios
                .get('https://www.googleapis.com/books/v1/volumes?q=' + this.searchTerm + '&key=' + this.gb_key)
                .then(
                    response => (
                        console.log('Searched for term ' + this.searchTerm),
                        console.log('at: ' + 'https://www.googleapis.com/books/v1/volumes?q=' + this.searchTerm + '&key=' + this.gb_key),
                        this.searchResults = response.data.items
                    )
                );
        },
        sortBooks(sortBy = 'order', sortDir = 'asc') {

            this.sortBy = sortBy;
            if (this.sortBy == sortBy) {
                this.sortDir = (this.sortDir == 'asc') ? 'desc' : 'asc';
            }

            this.books = _.orderBy(this.books, [this.sortBy], [this.sortDir]);

        },
        moveItemUp(book) {

            const index = this.books.indexOf(book);
            const nextUp = index - 1;

            this.books[nextUp].order = parseInt(this.books[index].order);
            this.books[index].order = parseInt(this.books[index].order) - 1;

            this.sortBy = 'order';
            this.sortDir = 'asc';
            this.books = _.orderBy(this.books, [this.sortBy], [this.sortDir]);
            this.showSaveOrder = true;

        },
        moveItemDown(book) {

            const index = this.books.indexOf(book);
            const nextDown = index + 1;

            this.books[nextDown].order = parseInt(this.books[index].order);
            this.books[index].order = parseInt(this.books[index].order) + 1;

            this.sortBy = 'order';
            this.sortDir = 'asc';
            this.books = _.orderBy(this.books, [this.sortBy], [this.sortDir]);
            this.showSaveOrder = true;

        },
        getBookOrder() {
            let bookOrder = {};
            let orderIndex = 0;
            // this.sortedBooks.forEach(book => {
            this.books.forEach(book => {
                bookOrder[orderIndex] = book.id;
                book.order = orderIndex;
                orderIndex++;
            });
            this.bookOrder = bookOrder;
        },
        normalizeBookDataFromSearch(book) {
            const normalizedBook = {
                title: book.volumeInfo.title,
                thumbnail: book.volumeInfo.imageLinks.thumbnail,
                description: book.volumeInfo.description,
                author: this.getAuthor(book.volumeInfo.authors),
                isbn: book.volumeInfo.industryIdentifiers[1].identifier,
                page_count: book.volumeInfo.pageCount,
                show: true,
                addToList: true
            };
            return normalizedBook;
        },
        addBook(book, normalize = true) {
            book = (normalize) ? this.normalizeBookDataFromSearch(book) : book;
            book.user_id = this.user_id;
            book.api_secret = this.api_secret;
            book.order = 0;
            book.read = 0;

            axios({
                    method: "POST",
                    url: '/api/books/store',
                    params: book
                })
                .then(
                    response => (
                        this.books.push(book),
                        this.getBookOrder,
                        console.log("Book Saved!")
                    )
                );
        },
        deleteBook(book) {
            const index = this.$data.books.indexOf(book);
            if (confirm("Are you sure that you want to delete " + book.title + "?")) {
                axios({
                        method: "DELETE",
                        url: '/api/books/' + book.id,
                        params: {
                            user_id: this.user_id,
                            api_secret: this.api_secret
                        }
                    })
                    .then(
                        response => (

                            this.$data.books.splice(index, 1),
                            this.getBookOrder,
                            console.log("Book Deleted!")
                        )
                    );
            } else {
                return;
            }
        },
        updateReadStatus(book) {
            book.user_id = this.user_id;
            book.api_secret = this.api_secret;
            book.read = (book.read == false) ? true : false;

            console.log(book.read);

            axios({
                    method: "PATCH",
                    url: '/api/books/status/' + book.id,
                    params: book
                })
                .then(
                    response => (
                        console.log("Book updated!")
                    )
                );

        },
        updateAllOrders() {

            console.log(this.bookOrder);

            axios({
                    method: "PATCH",
                    url: '/api/books/orders',
                    params: this.bookOrder
                })
                .then(
                    response => (
                        console.log("Order Saved"),
                        this.showSaveOrder = false
                    )
                );
        },
        saveOrder() {
            this.getBookOrder();
            this.updateAllOrders();
        },
        populateBookDetail(book, search = false) {
            book = (search) ? this.normalizeBookDataFromSearch(book) : book;
            this.bookDetail = {
                title: book.title,
                thumbnail: book.thumbnail,
                description: book.description,
                author: book.author,
                isbn: book.isbn,
                page_count: book.page_count,
                show: true,
                addToList: search
            };
        },
        getAuthor(authString) {
            if (authString !== undefined) {
                return authString.join(', ');
            } else {
                return '';
            }
        }
    },
    created() {
        axios
            .get("/api/books/" + this.user_id + "/" + this.api_secret)
            .then(
                response => {
                    // this.books = response.data.books;
                    this.books = _.orderBy(response.data.books, [this.sortBy], [this.sortDir]);
                    this.user = response.data.user;

                    if (this.books[1].order == 0) {
                        this.getBookOrder();
                        this.updateAllOrders();
                    }
                }

            );
    }
}
</script>
