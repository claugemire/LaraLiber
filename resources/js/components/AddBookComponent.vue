<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add A Book</div>



                    <!-- <form>
                        <input type="hidden" name="user_id" v-model="user_id"/>
                        <input type="hidden" name="api_secret" v-model="api_secret"/>

                        <label for="book-title">Title:</label>
                        <input type="text" name="book-title" v-model="newBook.title"/>

                        <label for="book-author">Author:</label>
                        <input type="text" name="book-author" v-model="newBook.author"/>

                        <label for="book-description">Description:</label>
                        <textarea name="book-description" v-model="newBook.description"></textarea>

                        <label for="book-page-count">Page Count:</label>
                        <input type="text" name="book-page-count" v-model="newBook.pageCount">

                        <label for="book-isbn">ISBN:</label>
                        <input type="text" name="book-isbn" v-model="newBook.isbn">

                        <label for="book-thumbnail">Thumbnail:</label>
                        <input type="text" name="book-thumbnail" v-model="newBook.thumbnail">
                    </form> -->




                    <input type="text" name="searchTerm" v-model="searchTerm"/>
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
                                    </div>

                                </div>
                            </div>
                    </div>

                    <div class="card-body">
                        <button @click="getBooks" class="btn btn-info">refresh</button>


                            <div class="card" v-for="book in books" :key="book.id">
                                <div class="card-body flex">
                                    <img :src="book.thumbnail" />
                                    <div>
                                        <h2>{{ book.title }}</h2>
                                        By: {{ book.author }}
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
                user: null,
                searchTerm: 'beach',
                searchResults: null,
                booksUrl: '/api/books/' + this.user_id + '/' + this.api_secret,
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
            addBook(book) {
                console.log(book);
            },
            getAuthor(authString) {
                if(authString !== undefined)
                {
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
