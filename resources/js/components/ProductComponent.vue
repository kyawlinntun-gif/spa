<template>
    <div class="container mt-5">
        <div class="row d-flex justify-content-end mb-3">

            <!-- start of create -->
            <div class="col-5">
                <button class="btn btn-primary" @click.prevent="create()"><i
                        class="fas fa-plus-circle mr-1"></i>Create</button>
            </div>
            <!-- end of create -->

            <!-- start of search -->
            <div class="col-3">
                <form @submit.prevent="getData">
                    <div class="input-group">
                        <input type="text" placeholder="search" class="form-control" v-model="search">
                        <div class="input-group-append"><button type="submit" class="btn btn-primary"><i
                                    class="fas fa-search"></i></button></div>
                    </div>
                </form>
            </div>
            <!-- end of search -->

        </div>
        <div class="row">

            <!-- start of form -->
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h1 class="mb-0">{{ isEditMode ? 'Edit' : 'Create' }}</h1>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger" v-if="form.errors.hasMessage()"
                            v-text="form.errors.getMessage()"></div>
                        <form @submit.prevent="insertData()" @keydown="
                        form.errors.clear($event.target.name)">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control"
                                    :class="{'is-invalid' : form.errors.has('name')}" name="name" id="name"
                                    v-model="form.name" placeholder="e.g. Item">
                                <div class="text-small text-danger" v-if="form.errors.has('name')"
                                    v-text="form.errors.get('name')"></div>
                            </div>
                            <div class="form-group">
                                <label for="price">Price:</label>
                                <input type="number" class="form-control"
                                    :class="{'is-invalid' : form.errors.has('price')}" name="price" id="price"
                                    v-model="form.price" placeholder="0">
                                <div class="text-small text-danger" v-if="form.errors.has('price')"
                                    v-text="form.errors.get('price')"></div>
                            </div>
                            <button type="submit" class="btn btn-primary"><i
                                    class="fas fa-save mr-1"></i>{{ isEditMode ? 'Update' : 'Save' }}</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- end of form -->

            <!-- start of table -->
            <div class="col-8">
                <table class="table table-striped" v-if="form.data.any()">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="data in form.data.data" v-bind:key="data.id">
                            <th scope="row">{{ data.id }}</th>
                            <td>{{ data.name }}</td>
                            <td>{{ data.price }}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click.prevent="editData(data)"><i
                                        class="fas fa-edit mr-1"></i>Edit</button>
                                <button class="btn btn-danger btn-sm" @click.prevent="deleteData(data.id)"><i
                                        class="fas fa-trash-alt mr-1"></i>Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- start of pagination -->
                <pagination :data="pagination" @pagination-change-page="getData"></pagination>
                <!-- end of pagination -->

            </div>
            <!-- end of table -->

        </div>
    </div>
</template>

<script>
    // Import the EventBus we just created.
    import {
        EventBus
    } from '../event-bus.js';

    class Form {
        constructor(item) {
            this.originalItem = item;

            for (let field in item) {
                this[field] = item[field];
            }

            // Data
            this.data = new Data;

            // Error
            this.errors = new Errors;
        }

        item() {
            let item = {};

            for (let field in this.originalItem) {
                item[field] = this[field];
            }

            return item;
        }

        get(url) {
            return this.getSubmit('get', url);
        }

        getSubmit(requestType, url) {
            return new Promise((resolve, reject) => {
                axios[requestType](url)
                    .then(response => {

                        this.data.get(response.data.data);
                        resolve(response.data);
                    })
                    .catch(errors => {

                        reject(errors.response.data);
                    });
            });
        }

        post(url) {
            return this.insertSubmit('post', url);
        }

        insertSubmit(requestType, url) {
            return new Promise((resolve, reject) => {
                axios[requestType](url, this.item())
                    .then(response => {

                        // EventBus.$emit('get-data');

                        this.reset();

                        this.get('/api/product');

                        resolve(response.data);
                    })
                    .catch(errors => {

                        this.errors.recordMessage(errors.response.data.message);
                        this.errors.record(errors.response.data.errors);

                        reject(errors.response.data);
                    });
            });
        }

        put(url) {
            return this.insertSubmit('put', url);
        }

        delete(url) {
            return this.destroy('delete', url);
        }

        destroy(requestType, url) {
            return new Promise((resolve, reject) => {
                axios[requestType](url)
                    .then(response => {
                        this.get('/api/product');
                        resolve(response.data);
                    })
                    .catch(errors => {
                        reject(errors.response.data);
                    });
            });
        }

        reset() {
            for (let field in this.originalItem) {
                this[field] = '';
            }
        }

    }

    class Data {
        constructor() {
            this.data = {};
        }

        get(data) {
            this.data = data;
        }

        any() {
            return Object.keys(this.data).length > 0;
        }
    }

    class Errors {
        constructor() {
            this.errors = {};
            this.message = '';
        }

        record(errors) {
            this.errors = errors;
        }

        get(field) {
            if (this.errors[field]) {
                return this.errors[field][0];
            }
        }

        has(field) {
            return this.errors.hasOwnProperty(field);
        }

        clear(field) {
            if (field) {
                delete this.errors[field];

                // this.message = '';

                if (Object.keys(this.errors).length <= 0) {
                    this.message = '';
                }

                return;
            }

            this.errors = {};
            this.message = '';
        }

        recordMessage(data) {
            this.message = data;
        }

        hasMessage() {
            return this.message;
        }

        getMessage() {
            return this.message;
        }

    }

    export default {
        data() {
            return {
                form: new Form({
                    id: '',
                    name: '',
                    price: ''
                }),
                isEditMode: false,
                pagination: {},
                search: ''
            }
        },

        methods: {
            getData(page = 1) {

                let loader = this.$loading.show();

                this.$Progress.start();
                this.form.get(`/api/product?page=${page}&search=${this.search}`)
                    .then(data => {

                        loader.hide();
                        
                        this.$Progress.finish();
                        this.pagination = data;
                    })
                    .catch(data => {

                        loader.hide();

                        this.$Progress.end();
                    });
            },

            insertData() {

                let loader = this.$loading.show();

                this.$Progress.start();

                if (!this.isEditMode) {
                    this.form.post('/api/product')
                        .then(data => {

                            loader.hide();

                            this.$Progress.finish();

                            Toast.fire({
                                icon: 'success',
                                title: 'Created successfully'
                            });
                        })
                        .catch(data => {

                            loader.hide();

                            this.$Progress.fail();

                        });
                    return;
                }

                this.form.put(`/api/product/${this.form.id}`)
                    .then(data => {

                        loader.hide();

                        this.$Progress.finish();

                        this.isEditMode = false;
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated successfully'
                        });
                    })
                    .catch(data => {

                        loader.hide();

                        this.$Progress.fail();

                    });

            },

            editData(product) {
                this.isEditMode = true;
                this.form.id = product.id;
                this.form.name = product.name;
                this.form.price = product.price;
                this.form.errors.clear();
            },

            create() {
                this.isEditMode = false;
                this.form.id = '';
                this.form.name = '';
                this.form.price = '';
                this.form.errors.clear();
            },

            deleteData(productId) {

                this.$Progress.start();

                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete'
                }).then((result) => {

                    if (result.isConfirmed) {

                        let loader = this.$loading.show();

                        loader.hide();

                        this.$Progress.finish();

                        this.form.delete(`/api/product/${productId}`);

                        // Swal.fire({
                        //     title: 'Deleted!',
                        //     icon: 'success'
                        // });

                        Toast.fire({
                            icon: 'success',
                            title: 'Deleted successfully'
                        });
                    }
                })
                .catch(errors => {

                    loader.hide();

                    this.$Progress.fail();
                });
            }

        },

        created() {
            this.getData();
        },

        mounted() {
            // EventBus.$on('get-data', this.getData());
        }
    }

</script>

<style>

</style>
