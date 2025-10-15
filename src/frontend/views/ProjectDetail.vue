<template>
  <div class="p-8">
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
    </div>
    
    <div v-else-if="project">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
          <button @click="$router.back()" class="flex items-center text-gray-600 hover:text-gray-900">
            <ArrowLeft class="w-5 h-5 mr-2" />
            Back to Projects
          </button>
          <div class="flex items-center space-x-3">
            <button class="btn btn-secondary">
              <Settings class="w-5 h-5" />
            </button>
            <button class="btn btn-primary">
              <Save class="w-5 h-5 mr-2" />
              Save Changes
            </button>
          </div>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-900">{{ project.name }}</h1>
        <div class="flex items-center space-x-4 mt-2">
          <span class="text-gray-600">{{ project.type }}</span>
          <span 
            class="px-3 py-1 text-xs font-medium rounded-full"
            :class="getStatusClass(project.status)"
          >
            {{ project.status }}
          </span>
        </div>
      </div>
      
      <!-- Kanban Board -->
      <div class="card mb-6">
        <h2 class="text-xl font-semibold mb-4">Task Board</h2>
        <div class="grid grid-cols-5 gap-4">
          <div v-for="column in kanbanColumns" :key="column.id" class="bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-3">
              <h3 class="font-medium text-gray-900">{{ column.name }}</h3>
              <span class="text-sm text-gray-500">{{ getTaskCount(column.id) }}</span>
            </div>
            
            <div class="space-y-3">
              <div 
                v-for="task in getTasksByColumn(column.id)" 
                :key="task.id"
                class="bg-white p-3 rounded-lg border border-gray-200 hover:shadow-md transition-shadow cursor-move"
              >
                <h4 class="font-medium text-sm text-gray-900 mb-2">{{ task.title }}</h4>
                <div class="flex items-center justify-between">
                  <span 
                    class="text-xs px-2 py-1 rounded"
                    :class="getPriorityClass(task.priority)"
                  >
                    {{ task.priority }}
                  </span>
                  <span v-if="task.story_points" class="text-xs text-gray-500">
                    {{ task.story_points }} pts
                  </span>
                </div>
              </div>
            </div>
            
            <button class="w-full mt-3 py-2 text-sm text-gray-600 hover:text-gray-900 border border-dashed border-gray-300 rounded-lg hover:border-gray-400 transition-colors">
              + Add Task
            </button>
          </div>
        </div>
      </div>
      
      <!-- Project Info -->
      <div class="grid grid-cols-3 gap-6">
        <div class="card">
          <h3 class="font-semibold mb-3">Project Details</h3>
          <dl class="space-y-2 text-sm">
            <div class="flex justify-between">
              <dt class="text-gray-600">Progress</dt>
              <dd class="font-medium">{{ project.progress }}%</dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-gray-600">Start Date</dt>
              <dd class="font-medium">{{ formatDate(project.start_date) }}</dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-gray-600">Deadline</dt>
              <dd class="font-medium">{{ formatDate(project.deadline) }}</dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-gray-600">Client</dt>
              <dd class="font-medium">{{ project.client_id || 'N/A' }}</dd>
            </div>
          </dl>
        </div>
        
        <div class="card">
          <h3 class="font-semibold mb-3">Team Members</h3>
          <div class="space-y-2">
            <div 
              v-for="member in project.team_members" 
              :key="member.id"
              class="flex items-center space-x-3"
            >
              <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white text-sm font-medium">
                {{ member.name.charAt(0) }}
              </div>
              <div>
                <p class="text-sm font-medium text-gray-900">{{ member.name }}</p>
                <p class="text-xs text-gray-500">{{ member.role }}</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="card">
          <h3 class="font-semibold mb-3">Git Repository</h3>
          <div class="space-y-3">
            <div class="flex items-center space-x-2 text-sm">
              <GitBranch class="w-4 h-4 text-gray-500" />
              <span class="text-gray-600">{{ project.git_repository || 'Not configured' }}</span>
            </div>
            <button class="btn btn-secondary w-full text-sm">
              <GitBranch class="w-4 h-4 mr-2" />
              View Branches
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useProjectsStore } from '../stores/projects'
import { ArrowLeft, Settings, Save, GitBranch } from 'lucide-vue-next'

const route = useRoute()
const projectsStore = useProjectsStore()

const loading = ref(true)
const project = computed(() => projectsStore.currentProject)

const kanbanColumns = [
  { id: 'backlog', name: 'Backlog' },
  { id: 'in-progress', name: 'In Progress' },
  { id: 'review', name: 'Review' },
  { id: 'testing', name: 'Testing' },
  { id: 'done', name: 'Done' }
]

const tasks = ref([
  { id: 1, title: 'Design Homepage', status: 'in-progress', priority: 'high', story_points: 8 },
  { id: 2, title: 'Implement Product Filter', status: 'backlog', priority: 'medium', story_points: 5 },
  { id: 3, title: 'Setup CI/CD Pipeline', status: 'done', priority: 'high', story_points: 3 },
  { id: 4, title: 'Write API Documentation', status: 'review', priority: 'medium', story_points: 5 }
])

const getTasksByColumn = (columnId) => {
  return tasks.value.filter(t => t.status === columnId)
}

const getTaskCount = (columnId) => {
  return getTasksByColumn(columnId).length
}

const getStatusClass = (status) => {
  const classes = {
    'in_progress': 'bg-blue-100 text-blue-800',
    'planning': 'bg-yellow-100 text-yellow-800',
    'completed': 'bg-green-100 text-green-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getPriorityClass = (priority) => {
  const classes = {
    'high': 'bg-red-100 text-red-800',
    'medium': 'bg-yellow-100 text-yellow-800',
    'low': 'bg-green-100 text-green-800'
  }
  return classes[priority] || 'bg-gray-100 text-gray-800'
}

const formatDate = (date) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

onMounted(async () => {
  await projectsStore.fetchProject(route.params.id)
  loading.value = false
})
</script>
