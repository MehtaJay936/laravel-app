import { reactive, ref } from "vue";
import axios from "axios";
import { defineStore } from "pinia";

export const usePlayerStore = defineStore("player", () => {
    const playersData = ref([]);
    const playerForm = reactive({
        name: "",
        email: "",
        date_of_birth: "",
    });

    const getPlayers = async () => {
        try {
            const { data } = await axios.get("/api/players");
            playersData.value = data.players;
        } catch (error) {
            console.error("Error fetching players:", error);
        }
    };

    const createPlayer = async () => {
        try {
            const response = await axios.post("/api/players", playerForm);
            return response.data;
        } catch (error) {
            console.error("Error creating player:", error);
            throw error;
        }
    };

    return { playersData, playerForm, getPlayers, createPlayer };
});
