<template>
    <v-alert
    v-if="message"
    :type="messageType"
    :title="messageType == 'success' ? 'Başarılı' : 'Hata'"
    :text="message"
  ></v-alert>
    <v-text-field label="Başlık" v-model="title" required type="text"></v-text-field>
    <v-file-input label="Resim" prepend-icon="mdi-camera" accept="image/png, image/jpeg, image/jpg" @change="onFileSelected"
        required></v-file-input>
    <v-textarea label="İçerik" v-model="content" required type="text"></v-textarea>
    <v-btn color="green" variant="tonal" @click="addBlog">Ekle</v-btn>
</template>

<script>
import axios from 'axios'
export default {
    name: 'AddBlog',
    data() {
        return {
            title: '',
            content: '',
            image: null,
            message: null,
            messageType: null
        }
    },
    methods: {  
        onFileSelected(event) {
            this.image = event.target.files[0];
        },
        addBlog() {
            // add blog with axios
            if (this.title || this.content || this.image) {
                const formData = new FormData()
                formData.append('title', this.title)
                formData.append('content', this.content)
                formData.append('image', this.image)
                console.log(formData)
                axios.post('http://localhost:8000/api/blog', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    }
                })
                    .then(response => {
                        console.log(response)
                        this.message = response.data.message
                        this.messageType = 'success'
                        this.title = ''
                        this.content = ''
                        this.image = null
                    })
                    .catch(error => {
                        console.log(error)
                        this.message = error.response.data.message
                        this.messageType = 'error'

                    })
            }
        }
    },
    created() {
        if (!localStorage.getItem('token')) {
            this.$router.push({ name: 'Home' })
        }
    },
    mounted() {

    }

}
</script>
