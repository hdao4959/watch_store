import { ClientApi } from "../../config"

export const logout = async () => {
    try {
        const token = localStorage.getItem('token');
        const {data} = await ClientApi.post('/logout', {}, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })

        

        if(data.success == true){
            localStorage.removeItem('token');
            localStorage.removeItem('account_name');
            window.location.href = '/'
            alert(data.message);
        }

        if(data.success == false){
            alert(data.message);
        }
    } catch (error) {
        alert(error.response.data.message);
    }
}

