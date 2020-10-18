<template>
<div class="container">



            <div>
                <div>
                    <div @click="sortBooks('title')" :class="{ 'alert-info': sortBy == 'title' }">Title <span v-if="sortBy == 'title'"><span v-if="sortDir =='asc'">&#9650;</span><span v-else>&#9660;</span></span></div>
                    <div @click="sortBooks('author')">Author</div>
                    <div @click="sortBooks('read')">Status</div>
                    <div @click="sortBooks('order')">My Order</div>
                    <div></div>
                </div>
            </div>
            <div class="flex" v-for="(book, index) in sortedBooks" :key="index">
                <div class="pr-3 pt-3">
                    <img class="w-24" :src="book.thumbnail" />
                </div>
                <div>
                    <h2 class="text-2xl">{{ book.title }}</h2>
                    {{ book.author }}
                </div>
                <div class="alert alert-success" v-if="book.read == true">You've read this book, Congrats!!</div>
                <div>{{ book.order }}</div>
                <div>
                    <button @click="populateBookDetail(book, false)" class="btn btn-info btn-sm">&#9728; More Info</button><br/>
                    <button @click="deleteBook(book)" class="btn btn-danger btn-sm">&#9747; Remove</button>
                    <button @click="updateReadStatus(book)" class="btn btn-success btn-sm" v-if="book.read == false">&#9745; Mark as Read</button>
                    <button @click="updateReadStatus(book)" class="btn btn-warning btn-sm" v-if="book.read == true">&#9745; Oops, Mark as Unread</button>
                    <hr/>
                    <button class="btn btn-secondary btn-sm">&#8595;</button>
                    <button class="btn btn-secondary btn-sm">&#8593;</button>
                </div>
            </div>



    <div class="row justify-content-center">

        <div id="details" class="details col-md-4 bg-aqua" v-show="bookDetail.show">
            <h2>{{ bookDetail.title }}</h2>
            <img :src="bookDetail.thumbnail" />
            <p>
                {{ bookDetail.description }}
            </p>
            <p>{{ bookDetail.author }}</p>
            <p>{{ bookDetail.isbn }}</p>
            <p> Page Count: {{ bookDetail.page_count }}</p>
            <button @click="addBook(bookDetail, false)" class="btn btn-success btn-sm" v-show="bookDetail.addToList">&#9733; Add To My List</button>
        </div>



        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Search For A Book To Add</div>

                <input type="text" name="searchTerm" v-model="searchTerm" />
                <button @click="searchBooks" class="btn btn-info">&#9906; Search</button>
                <div id="search-results">
                    <div class="card" v-for="result in searchResults" :key="result.id">
                        <div class="card-body flex">
                            <img :src="result.volumeInfo.imageLinks.thumbnail" />
                            <div>
                                <h2>{{ result.volumeInfo.title }}</h2>

                                result.volumeInfo.authors.isEmpty()
                                By: {{ getAuthor(result.volumeInfo.authors) }}
                                <button @click="addBook(result)" class="btn btn-success btn-sm">&#9733; Add To My List</button>
                                <button @click="populateBookDetail(result, true)" class="btn btn-info btn-sm">&#9728; More Info</button>
                            </div>

                        </div>
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
            user: null,
            searchTerm: 'beach',
            searchResults: null,
            sortBy: 'order',
            sortDir: 'asc',
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
    computed: {
        sortedBooks: function () {
            const sorted = _.orderBy(this.books, [this.sortBy], [this.sortDir]);
            return sorted;
        }
    },
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
        sortBooks(sortBy='order', sortDir='asc') {
            this.sortBy = sortBy;
            if(this.sortBy == sortBy){
                this.sortDir = (this.sortDir == 'asc') ? 'desc' : 'asc';
            }
            console.log(this.sortBy + " | " + this.sortDir);

        },
        getBooks(orderBy = null, dir = null) {
            axios({
                    method: "GET",
                    url: "/api/books/" + this.user_id + "/" + this.api_secret,
                    params: {
                        'order_by': 'title',
                        'dir': dir
                    }
                })
                .then(
                    response => (
                        this.books = response.data.books,
                        this.user = response.data.user,
                        console.log("books are refreshed")
                    )
                );
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

            axios({
                    method: "POST",
                    url: '/api/books/store',
                    params: book
                })
                .then(
                    response => (
                        this.books.push(book),
                        console.log("Book Saved!")
                    )
                );
        },
        deleteBook(book) {
            const index = this.books.indexOf(book);
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

                        this.books.splice(index, 1),

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
        updateOrder(book, normalize = true) {
            book = (normalize) ? this.normalizeBookDataFromSearch(book) : book;
            book.user_id = this.user_id;
            book.api_secret = this.api_secret;

            axios({
                    method: "POST",
                    url: '/api/books/store',
                    params: book
                })
                .then(
                    response => (
                        this.books.push(book),
                        console.log("Book Saved!")
                    )
                );
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
                response => (
                    this.books = response.data.books,
                    this.user = response.data.user
                )
            );
    }
}
</script>
