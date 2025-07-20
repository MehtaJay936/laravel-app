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

      </v-card>
    </div>
  </AuthenticatedLayout>
</template>
