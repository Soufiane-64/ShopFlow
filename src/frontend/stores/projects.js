import { defineStore } from 'pinia'
import axios from '../utils/axios'

export const useProjectsStore = defineStore('projects', {
  state: () => ({
    projects: [],
    currentProject: null,
    loading: false
  }),
  
  actions: {
    async fetchProjects() {
      this.loading = true
      try {
        const response = await axios.get('/api/projects')
        if (response.data.success) {
          this.projects = response.data.data
        }
      } catch (error) {
        console.error('Failed to fetch projects:', error)
      } finally {
        this.loading = false
      }
    },
    
    async fetchProject(id) {
      this.loading = true
      try {
        const response = await axios.get(`/api/projects/${id}`)
        if (response.data.success) {
          this.currentProject = response.data.data
        }
      } catch (error) {
        console.error('Failed to fetch project:', error)
      } finally {
        this.loading = false
      }
    },
    
    async createProject(projectData) {
      try {
        const response = await axios.post('/api/projects', projectData)
        if (response.data.success) {
          this.projects.push(response.data.data)
          return response.data.data
        }
      } catch (error) {
        console.error('Failed to create project:', error)
        throw error
      }
    },
    
    async updateProject(id, projectData) {
      try {
        const response = await axios.put(`/api/projects/${id}`, projectData)
        if (response.data.success) {
          const index = this.projects.findIndex(p => p.id === id)
          if (index !== -1) {
            this.projects[index] = response.data.data
          }
          return response.data.data
        }
      } catch (error) {
        console.error('Failed to update project:', error)
        throw error
      }
    },
    
    async deleteProject(id) {
      try {
        const response = await axios.delete(`/api/projects/${id}`)
        if (response.data.success) {
          this.projects = this.projects.filter(p => p.id !== id)
          return true
        }
      } catch (error) {
        console.error('Failed to delete project:', error)
        throw error
      }
    }
  }
})
