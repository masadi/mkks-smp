<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card card-default">
          <div class="card-header">Login</div>
          <div v-if="error">
            <div class="alert bg-danger text-white m-4">
              {{ error }}
            </div>
          </div>
          <div class="card-body">
            <form @submit.prevent="submit">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" v-model="email" class="form-control" required />
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" v-model="password" class="form-control" required />
              </div>
              <button type="submit" class="btn btn-primary">Login</button>
            </form>
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
      email: "",
      password: "",
      error: "",
    };
  },
  methods: {
    submit() {
      axios
        .post("api/login", {
          email: this.email,
          password: this.password,
        })
        .then((response) => {
          let data = response.data;
          localStorage.setItem("user", JSON.stringify(data.user));
          localStorage.setItem("jwt", data.token);
          this.$emit("setData");

          this.$router.push({ name: "user", params: { userId: data.user.user_id } });
        })
        .catch((error) => {
          this.error = error.response.data.error;
        });
    },
  },
};
</script>
