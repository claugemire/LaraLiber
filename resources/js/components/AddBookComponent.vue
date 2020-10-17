<template>
<div class="container">
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
            <button @click="addBook(bookDetail, false)" class="btn btn-info" v-show="bookDetail.addToList">Add To My List</button>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Search For A Book To Add</div>

                <input type="text" name="searchTerm" v-model="searchTerm" />
                <button @click="searchBooks" class="btn btn-info">search</button>
                <div id="search-results">
                    <div class="card" v-for="result in searchResults" :key="result.id">
                        <div class="card-body flex">
                            <img :src="result.volumeInfo.imageLinks.thumbnail" />
                            <div>
                                <h2>{{ result.volumeInfo.title }}</h2>

                                result.volumeInfo.authors.isEmpty()
                                By: {{ getAuthor(result.volumeInfo.authors) }}
                                <button @click="addBook(result)" class="btn btn-info">Add To My List</button>
                                <button @click="populateBookDetail(result, true)" class="btn btn-info">More Info</button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <button @click="getBooks" class="btn btn-info">refresh</button>

                    <div class="card" v-for="(book, index) in books" :key="index">
                        <div class="card-body flex">
                            <img :src="book.thumbnail" />
                            <div>
                                <h2>{{ book.title }}</h2>
                                <h3>{{ book.id }}</h3>
                                By: {{ book.author }}
                            </div>
                            <button @click="populateBookDetail(book, false)" class="btn btn-info">More Info</button>
                            <button @click="deleteBook(book)" class="btn btn-warning">Remove</button>
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
            user: null,
            searchTerm: 'beach',
            searchResults: null,
            booksUrl: '/api/books/' + this.user_id + '/' + this.api_secret,
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
        getBooks() {
            axios
                .get(this.booksUrl)
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
                user_id: this.user_id,
                api_secret: this.api_secret,
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
            .get(this.booksUrl)
            .then(
                response => (
                    this.books = response.data.books,
                    this.user = response.data.user
                )
            );
    }
}
</script>
