<template>
    <v-card>
        <v-card>
            <v-img :src="blog.image" height="200px" cover>
            </v-img>
            <v-card-title>{{ blog.title }}</v-card-title>
            <v-card-text>
                {{ blog.content }}
            </v-card-text>
            <v-card-actions class="align-center d-flex">
                <router-link :to="{ name: 'Blog', params: { id: blog.id } }">
                    <v-btn variant="text" color="green-accent-4" text>Devamını Oku</v-btn>
                </router-link>
            </v-card-actions>
        </v-card>
    </v-card>
</template>
<script>
export default {
    name: 'BlogCard',
    props: {
        blog: {
            type: Object,
            required: true
        }
    }
}
</script>