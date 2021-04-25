<template>
<div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form @submit.prevent="login()">
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Username</label>

                            <div class="col-md-6">
                                <input v-model="form.email" id="email" type="email" class="form-control" name="email" :class="{ 'is-invalid': form.errors.has('email') }" required autocomplete="email" autofocus>
                                <has-error :form="form" field="email"></has-error>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                            <div class="col-md-6">
                                <input v-model="form.password" id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" v-model="form.remember">

                                    <label class="form-check-label" for="remember">
                                        Ingat Saya
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Masuk
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</template>

<script>
import _ from 'lodash'
export default {
    mounted() {
        console.log('Component mounted.')
    },
    methods: {
        login(){
            console.log('login');
        }
    },
}
</script>
