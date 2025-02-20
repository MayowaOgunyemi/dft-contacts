import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

// Import axios
import axios from 'axios'

// Make the Axios request
axios.get('/api/users') // This will be the request to the API endpoint- It has been shortened for brevity through the server proxy in the vite.config.ts file
  .then(response => {
    console.log(response.data);  // Handle the response data here
  })
  .catch(error => {
    console.error(error);  // Handle any errors here
  })

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
