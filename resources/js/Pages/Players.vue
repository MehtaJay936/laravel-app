<script setup>
import { ref, onMounted } from 'vue'
import draggable from 'vuedraggable'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3';

const unassignedPlayers = ref([])
const teams = ref([])
const search = ref('')

const getData = async () => {
  const { data } = await axios.get('/api/players')
  unassignedPlayers.value = data.unassignedPlayers
  teams.value = data.teams.map(team => ({
    ...team,
    players: team.players ?? []
  }))
}

onMounted(async () => {
  getData()
})

const savePlayers = async () => {
  const payload = {
    teams: teams.value.map(team => ({
      team_id: team.id,
      players: team.players.map((player, index) => ({
        player_id: player.id,
        sort_order: index
      }))
    })),
    unassigned: unassignedPlayers.value.map(p => p.id)
  }

  await axios.post('/api/players', payload)
  alert('Teams saved!')
  getData()
}

const headersList = [
  { title: 'ID', key: 'id', align: 'start' },
  { title: 'Name', key: 'name', align: 'center' },
  { title: 'Email', key: 'email', align: 'center' },
  { title: 'Date of  Birth', key: 'date_of_birth', align: 'center' },
  { title: 'Actions', key: 'actions', align: 'center', sortable: false },
]
</script>

<template>
  <Head title="Players" />

  <AuthenticatedLayout>
    <div class="p-4">

      <v-card flat>

        <v-card-title class="d-flex align-center pe-5">
          <v-text-field v-model="search" density="compact" label="Search" prepend-inner-icon="mdi-magnify"
            variant="outlined" flat hide-details single-line clearable></v-text-field>

          <v-spacer></v-spacer>

          <v-btn prepend-icon="mdi-plus" color="primary" @click="create" rounded="xl">Create</v-btn>
          
        </v-card-title>

        <v-divider></v-divider>

        <v-data-table :headers="headersList" :items="unassignedPlayers" :search="search" class="elevation-1">
          <template v-slot:item.actions="{ item }">
            <div class="d-flex ga-2 justify-center">
              <v-icon color="primary" icon="mdi-pencil" size="small" @click="edit(item.id)" title="Edit">
                <v-tooltip>
                  Edit Player
                  </v-tooltip>
              </v-icon>

              <v-icon color="red" icon="mdi-delete" size="small" @click="remove(item.id)" title="Delete"></v-icon>
            </div>
          </template>
        </v-data-table>

        <!-- Unassigned -->
        <div class="mb-6">
          <h3 class="text-lg font-semibold mb-2">Unassigned Players</h3>
          <draggable v-model="unassignedPlayers" group="players" item-key="id" class="flex flex-wrap gap-4">
            <template #item="{ element }">
              <div class="p-3 w-50 bg-white rounded shadow border border-black">
                <p><strong>{{ element.name }}</strong></p>
                <p class="text-sm">{{ element.email }}</p>
                <p class="text-xs">{{ element.date_of_birth }}</p>
              </div>
            </template>
          </draggable>
        </div>

        <!-- Teams -->
        <h3 class="text-lg font-semibold mb-2">Teams</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div v-for="team in teams" :key="team.id" class="p-4 border bg-blue-50 rounded">
            <h4 class="text-lg font-bold">{{ team.name }}</h4>
            <p class="text-sm text-gray-600">{{ team.state }}, {{ team.country }}</p>

            <draggable v-model="team.players" group="players" item-key="id"
              class="min-h-[100px] mt-3 p-2 bg-white border rounded">
              <template #item="{ element }">
                <div class="p-2 bg-gray-100 border rounded mb-2">
                  <p><strong>{{ element.name }}</strong></p>
                </div>
              </template>
            </draggable>
          </div>
        </div>

        <div class="text-center">
          <button class="text-center mt-6 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
            @click="savePlayers">
            Save
          </button>
        </div>

      </v-card>
    </div>
  </AuthenticatedLayout>
</template>
