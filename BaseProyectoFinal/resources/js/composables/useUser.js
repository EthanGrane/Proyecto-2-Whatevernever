// useUsers.js
import axios from 'axios'

export function useUsers() {
    const updateUserImage = async (file) => {
        if (!file) return;

        const formData = new FormData();
        formData.append("image", file);

        try {
            const response = await axios.post("http://127.0.0.1:8000/api/users/updateimg", formData, {
                headers: { "Content-Type": "multipart/form-data" },
            });
            return { success: true, data: response.data };
        } catch (err) {
            console.error(err);
            return { success: false, error: err };
        }
    };

    const updateUserName = async (updatedName) => {
        try {
            const response = await axios.post("http://127.0.0.1:8000/api/users/updateusername", {
                username: updatedName,
            });

            return { success: true, data: response.data };
        }
        catch (err) {
            return { success: false, error: err };
        }
    }

    const updateUserDescription = async (updatedDescription) => {
        try {
            const response = await axios.post("http://127.0.0.1:8000/api/users/updatedescription", {
                desc: updatedDescription,
            });

            return { success: true, data: response.data };
        }
        catch (err) {
            return { success: false, error: err };
        }
    }

    return {
        updateUserImage,
        updateUserName,
        updateUserDescription
    };
}
