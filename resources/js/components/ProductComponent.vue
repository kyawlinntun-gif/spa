<template>
    <div class="container mt-5">
        <div class="row d-flex justify-content-end mb-3">
            <div class="col-5">
                <button class="btn btn-primary"><i class="fas fa-plus-circle mr-1"></i>Create</button>
            </div>
            <div class="col-3">
                <form action="">
                    <div class="input-group">
                        <input type="text" placeholder="search" class="form-control">
                        <div class="input-group-append"><button type="submit" class="btn btn-primary"><i
                                    class="fas fa-search"></i></button></div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h1 class="mb-0">Create</h1>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="text" class="form-control" name="price" id="price">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Save</button>
                    </div>
                </div>
            </div>
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
                                <button class="btn btn-success btn-sm"><i class="fas fa-edit mr-1"></i>Edit</button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt mr-1"></i>Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    class Form {
        constructor()
        {
            this.data = new Data;
        }

        get(url)
        {
            this.submit('get', url);
        }

        submit(requestType, url)
        {
            return new Promise((resolve, reject) => {
                axios[requestType](url)
                .then(response => {
                    this.data.get(response.data);
                    resolve(response.data);
                })
                .catch(errors => {

                    reject(errors.response.data);
                });
            });
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
                form: new Form
            }
        },

        methods: {
            getData()
            {
                this.form.get('/api/product');
            }  
        },
        
        created() {
            this.getData();
        }
    }

</script>

<style>

</style>
