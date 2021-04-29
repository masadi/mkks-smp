<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-light bg-info shadow-sm">
      <router-link :to="{ name: 'home' }" class="navbar-brand">Navbar</router-link>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-4">
          <li class="nav-item active">
            <router-link :to="{ name: 'home' }" class="nav-link"
              >Home <span class="sr-only">(current)</span></router-link
            >
          </li>
        </ul>
        <form class="form-inline my-2 my-lg-0 ml-4">
          <input
            class="form-control mr-sm-2"
            type="search"
            placeholder="Search"
            aria-label="Search"
          />
          <button class="btn btn-outline-dark text-dark my-2 my-sm-0" type="submit">
            Search
          </button>
        </form>
        <ul class="navbar-nav ml-auto">
          <template v-if="!isLoggedIn">
            <li class="nav-item">
              <router-link :to="{ name: 'login' }" class="nav-link">Login</router-link>
            </li>
            <li class="nav-item">
              <router-link :to="{ name: 'register' }" class="nav-link"
                >Register</router-link
              >
            </li>
          </template>
          <template v-if="isLoggedIn">
            <li class="nav-item">
              <router-link
                :to="{ name: 'user', params: { userId: user.user_id } }"
                class="nav-link"
                >{{ user.name }}</router-link
              >
            </li>
            <li class="nav-item">
              <a href="javascript:void(0)" class="nav-link" @click="logout"> Logout</a>
            </li>
          </template>
        </ul>
      </div>
    </nav>
    <main class="pt-4">
      <router-view @setData="getData"></router-view>
    </main>
  </div>
</template>
<script>
export default {
  data() {
    return {
      user: null,
      isLoggedIn: null,
    };
  },
  beforeMount() {
    this.getData();
  },
  methods: {
    getData() {
      this.isLoggedIn = localStorage.getItem("jwt") !== null;
      this.user = JSON.parse(localStorage.getItem("user"));
    },
    logout() {
      localStorage.removeItem("jwt");
      localStorage.removeItem("user");
      this.getData();
      this.$router.push("/");
    },
  },
};
</script>
