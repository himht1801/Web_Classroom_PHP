function showDeleteClass(key,name){
    let nameDel = document.getElementById('nameClassDel');
    let keyDel = document.getElementById('key-delete');
    nameDel.innerHTML = name;
    keyDel.value= key;
}

function showEditClass(ten,mota, phanhoc, phonghoc, chude, codelop){
    let keyEdit = document.getElementById('key-edit');
    let txtTen = document.getElementById('name-class');
    let txtMota = document.getElementById('desc-class');
    let txtPhonghoc = document.getElementById('room-class');
    let txtPhanhoc = document.getElementById('part-class');
    let txtChude = document.getElementById('chude-class');
    keyEdit.value = codelop;
    txtChude.value = chude;
    txtTen.value = ten;
    txtMota.value = mota;
    txtPhanhoc.value = phanhoc;
    txtPhonghoc.value = phonghoc;
}

function showModalDeletePerson(username,name){
    let tvTen = document.getElementById('nameDelete');
    let vlTen = document.getElementById('user-delete');
    tvTen.innerHTML = name;
    vlTen.value = username;
}

function showModalEditRole(username,name){
    let tvTen = document.getElementById('nameEditRole');
    let vlUsername = document.getElementById('username-edit');
    tvTen.innerHTML = name;
    vlUsername.value = username;
}

function showModalDelBaidang(idbaidang){
    let validBaidang = document.getElementById('delete-baidang-value');
    validBaidang.value = idbaidang;
}

function showModalEditBaidang(idbaidang,noidungbd){
    let vaidbaidang = document.getElementById('id-edit-baidang');
    vaidbaidang.value = idbaidang;
    let tvnoidung = document.getElementById('nd-baidang');
    tvnoidung.innerHTML = noidungbd;
}