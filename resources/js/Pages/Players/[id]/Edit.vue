<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, usePage } from '@inertiajs/vue3';
import { onBeforeMount, ref } from 'vue';
import { usePlayerStore } from '@/Store/playerStore.js'
import { storeToRefs } from 'pinia';

const playerStore = usePlayerStore();
const { playerForm } = storeToRefs(playerStore);

const valid = ref(false)
const form = ref(null)
const loading = ref(false)
const page = usePage()

const nameRules = [
  value => {
    if (value) return true

    return 'Name is required.'
  },
]

const emailRules = [
  value => {
    if (value) return true

    return 'Email is required.'
  },
]

const dateRules = [
  value => {
    if (value) return true

    return 'Date of Birth is required.'
  },
]

onBeforeMount(() => {
  playerStore.fetchPlayer(page.props.id);
})

const updatePlayer = async () => {
  const { valid } = await form.value.validate();

  if (valid) {
    loading.value = true
    playerStore.updatePlayer()
      .then(() => {
        loading.value = false
        router.visit(route('players'));
      })
      .catch(() => {
        loading.value = false
      });
  }
};

</script>

<template>

  <Head title="Edit Players" />

  <AuthenticatedLayout>
    <div class="p-4">
      <v-card flat>

        <v-form ref="form" v-model="valid" @submit.prevent="updatePlayer">
          <v-container>
            {{ playerForm }}
            <v-row>
              <v-col cols="12" md="4">
                <v-text-field v-model="playerForm.name" :rules="nameRules" label="Name" variant="outlined" required
                  clearable></v-text-field>
              </v-col>

              <v-col cols="12" md="4">
                <v-text-field v-model="playerForm.email" :rules="emailRules" label="E-mail" variant="outlined" required
                  clearable></v-text-field>
              </v-col>

              <v-col cols="12" md="4">
                <v-text-field v-model="playerForm.date_of_birth" label="Date of Birth" variant="outlined" required
                  type="date" clearable :rules="dateRules"></v-text-field>
              </v-col>

              <v-col cols="12" md="4">
                <v-avatar v-if="playerForm.logo" size="80">
                  <v-img :src="playerForm.logo" />
                </v-avatar>
              </v-col>
            </v-row>

            <v-row justify="center" class="mb-2">
              <v-btn type="submit" color="primary" rounded="xl" :loading="loading" :disabled="loading">Update</v-btn>
            </v-row>
          </v-container>
        </v-form>

      </v-card>
    </div>
  </AuthenticatedLayout>
</template>
