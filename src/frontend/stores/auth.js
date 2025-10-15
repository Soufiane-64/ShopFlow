import { defineStore } from 'pinia'
import axios from '../utils/axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    refreshToken: localStorage.getItem('refreshToken') || null
  }),
  
  getters: {
    isAuthenticated: (state) => !!state.token,
    currentUser: (state) => state.user
  },
  
  actions: {
    async login(email, password) {
      try {
        const response = await axios.post('/api/auth/login', { email, password })
        
        if (response.data.success) {
          this.token = response.data.data.token
          this.refreshToken = response.data.data.refresh_token
          this.user = response.data.data.user
          
          localStorage.setItem('token', this.token)
          localStorage.setItem('refreshToken', this.refreshToken)
          
          return true
        }
        return false
      } catch (error) {
        console.error('Login failed:', error)
        return false
      }
    },
    
    async register(name, email, password) {
      try {
        const response = await axios.post('/api/auth/register', { name, email, password })
        
        if (response.data.success) {
          this.token = response.data.data.token
          this.refreshToken = response.data.data.refresh_token
          this.user = response.data.data.user
          
          localStorage.setItem('token', this.token)
          localStorage.setItem('refreshToken', this.refreshToken)
          
          return true
        }
        return false
      } catch (error) {
        console.error('Registration failed:', error)
        return false
      }
    },
    
    logout() {
      this.user = null
      this.token = null
      this.refreshToken = null
      
      localStorage.removeItem('token')
      localStorage.removeItem('refreshToken')
    },
    
    checkAuth() {
      if (this.token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
      }
    }
  }
})
