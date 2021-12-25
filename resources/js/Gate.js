export default class Gate{

    constructor(user){
        this.user = user;
    }

    isAdmin(){
        return this.user.type === 'admin';
    }
    isSekolah(){
        return this.user.type === 'sekolah';
    }
    isDinas(){
        return this.user.type === 'dinas';
    }
    isPengawas(){
        return this.user.type === 'pengawas';
    }
    isBendahara(){
        return this.user.type === 'bendahara';
    }
    isKorwil(){
        return this.user.type === 'korwil';
    }
    isUser(){
        return this.user.type === 'user';
    }
    
    isAdminOrUser(){
        if(this.user.type === 'user' || this.user.type === 'admin'){
            return true;
        }
    }
}

