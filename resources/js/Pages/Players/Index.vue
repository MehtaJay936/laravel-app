<script setup>
import { ref, onBeforeMount } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3';
import { usePlayerStore } from '@/Store/playerStore.js'
import { storeToRefs } from 'pinia';

const playerStore = usePlayerStore();
const { playersData } = storeToRefs(playerStore);
const deleteDialog = ref(false)
const search = ref('');
const loading = ref(false)
const currentID = ref(null)

onBeforeMount(async () => {
  await playerStore.getPlayers();
})

const showDeleteDialog = (id) => {
  currentID.value = id;
  deleteDialog.value = true;
}

const deletePlayer = () => {
  loading.value = true
  playerStore.deletePlayer(currentID.value)
    .then(() => {
      loading.value = false
      deleteDialog.value = false;
      playerStore.getPlayers();
    })
    .catch(() => {
      loading.value = false
    });;
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

          <v-btn prepend-icon="mdi-plus" color="primary" :href="route('players-create')" rounded="xl">Create</v-btn>

        </v-card-title>

        <v-divider></v-divider>

        <v-data-table :headers="headersList" :items="playersData" :search="search" class="elevation-1">
          <template v-slot:item.actions="{ item }">
            <div class="d-flex ga-2 justify-center">
              <Link :href="route('players-edit', item.id)">
              <v-icon color="primary" icon="mdi-pencil" size="small" title="Edit">
              </v-icon>
              </Link>

              <v-icon color="red" icon="mdi-delete" size="small" @click="showDeleteDialog(item.id)" title="Delete">
              </v-icon>
            </div>
          </template>
        </v-data-table>

        <v-dialog v-model="deleteDialog" max-width="1000">
          <v-card title="Delete Player">
            <v-card-text>
              Are you sure you want to delete this player?
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions class="bg-surface-light">
              <v-btn text="Cancel" variant="plain" @click="deleteDialog = false"></v-btn>

              <v-btn text="Delete" color="red" @click="deletePlayer()" :loading="loading" :disabled="loading"></v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

      </v-card>
    </div>
  </AuthenticatedLayout>
</template>
