

export const goBack = (router) => {
    if(window.history.length > 1){
        router.back();
    }else{
        router.push("/");
    }
}
