<template>
  <v-row>
    <v-col cols="12" md="6" lg="4" xl="3" v-for="blog in blogs" :key="blog.id">
      <BlogCard :blog="blog"/>
    </v-col>

  </v-row>
</template>

<script>
  import BlogCard from './components/BlogCard.vue'
  import axios from 'axios'
  export default {
    name: 'HomePage',
    components: {
      BlogCard
    },
    data() {
      return {
        blogs: [],
      }
    },
    mounted() {
      this.getBlogs()
    },
    methods: {
      getBlogs() {
        axios.get('http://localhost:8000/api/blog')
          .then(res => {
            console.log(res.data)
            res.data.forEach(blog => {
              blog.image = 'http://localhost:8000/images/' + blog.image
              blog.content = blog.content.substring(0, 100) + '...'
            })
            this.blogs = res.data
          })
          .catch(err => {
            console.log(err)
          })
      }
    }
  }
</script>
