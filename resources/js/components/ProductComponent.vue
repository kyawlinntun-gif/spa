<template>
    <div class="container mt-5">
        <div class="row d-flex justify-content-end mb-3">

            <!-- start of create -->
            <div class="col-5">
                <button class="btn btn-primary" @click.prevent="create()"><i class="fas fa-plus-circle mr-1"></i>Create</button>
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
                        <form @submit.prevent="insertData()">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" name="name" id="name" v-model="form.name" placeholder="e.g. Item">
                            </div>
                            <div class="form-group">
                                <label for="price">Price:</label>
                                <input type="number" class="form-control" name="price" id="price" v-model="form.price" placeholder="0">
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>{{ isEditMode ? 'Update' : 'Save' }}</button>
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
                                <button class="btn btn-success btn-sm" @click.prevent="editData(data)"><i class="fas fa-edit mr-1"></i>Edit</button>
                                <button class="btn btn-danger btn-sm" @click.prevent="deleteData(data.id)"><i class="fas fa-trash-alt mr-1"></i>Delete</button>
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
    import { EventBus } from '../event-bus.js';

    class Form {
        constructor(item)
        {
            this.originalItem = item;

            for (let field in item)
            {
                this[field] = item[field];
            }

            this.data = new Data;
        }

        item()
        {
            let item = {};

            for (let field in this.originalItem)
            {
                item[field] = this[field];
            }

            return item;
        }

        get(url)
        {
            return this.getSubmit('get', url);
        }

        getSubmit(requestType, url)
        {
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

        post(url)
        {
            return this.insertSubmit('post', url);
        }

        insertSubmit(requestType, url)
        {
            return new Promise((resolve, reject) => {
                axios[requestType](url, this.item())
                .then(response => {

                    // EventBus.$emit('get-data');

                    this.reset();

                    this.get('/api/product');

                    resolve(response.data);
                })
                .catch(errors => {

                    reject(errors.response.data);
                });
            });
        }

        put(url)
        {
            return this.insertSubmit('put', url);
        }

        delete(url)
        {
            return this.destroy('delete',url);
        }

        destroy(requestType, url)
        {
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

        reset()
        {
            for (let field in this.originalItem)
            {
                this[field] = '';
            }
        }

    }

    class Data {
        constructor() {
            this.data = {};
        }

        get(data)
        {
            this.data = data;
        }

        any()
        {
            return Object.keys(this.data).length > 0;
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
            getData(page=1)
            {
                this.form.get(`/api/product?page=${page}&search=${this.search}`)
                .then(data => this.pagination = data);
            },

            insertData()
            {
                if(!this.isEditMode)
                {
                    this.form.post('/api/product');
                    return;
                }

                this.form.put(`/api/product/${this.form.id}`)
                .then(response => this.isEditMode = false);
                
            },

            editData(product)
            {
                this.isEditMode = true;
                this.form.id = product.id;
                this.form.name = product.name;
                this.form.price = product.price;
            },

            create()
            {
                this.isEditMode = false;
                this.form.id = '';
                this.form.name = '';
                this.form.price = '';
            },

            deleteData(productId)
            {
                let checkConfirm = confirm('Are you sure?');
                if(checkConfirm)
                {
                    this.form.delete(`/api/product/${productId}`);
                }
            }

        },
        
        created() {
            this.getData();
        },

        mounted()
        {
            // EventBus.$on('get-data', this.getData());
        }
    }

</script>

<style>

</style>
