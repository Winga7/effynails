// Service de gestion des tarifs avec API
import axios from "axios";

export const tarifService = {
    // Récupérer tous les tarifs
    async getTarifs() {
        try {
            const response = await axios.get("/api/tarifs");
            return response.data;
        } catch (error) {
            console.error("Erreur lors de la récupération des tarifs:", error);
            return [];
        }
    },

    // Ajouter un tarif
    async addTarif(tarif) {
        try {
            const response = await axios.post("/api/tarifs", tarif);
            return response.data;
        } catch (error) {
            console.error("Erreur lors de l'ajout du tarif:", error);
            throw error;
        }
    },

    // Mettre à jour un tarif
    async updateTarif(tarif) {
        try {
            const response = await axios.put(`/api/tarifs/${tarif.id}`, tarif);
            return response.data;
        } catch (error) {
            console.error("Erreur lors de la mise à jour du tarif:", error);
            throw error;
        }
    },

    // Supprimer un tarif
    async deleteTarif(id) {
        try {
            await axios.delete(`/api/tarifs/${id}`);
            return true;
        } catch (error) {
            console.error("Erreur lors de la suppression du tarif:", error);
            throw error;
        }
    },
};

export default tarifService;
