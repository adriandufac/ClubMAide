// Récupération des champs de sorties

let names = document.querySelectorAll('.name');
let dates = document.querySelectorAll('.date_debut');
let clotures = document.querySelectorAll('.cloture');
let places = document.querySelectorAll('.place');
let etats = document.querySelectorAll('.etat');
let inscrits = document.querySelectorAll('.inscrit');
let organisateurs = document.querySelectorAll('.organisateur');
let actions= document.querySelectorAll('.action');

// Récupération des checKBox
let isOrganisateur = document.getElementById('accueil_filtrage_form_isInscrit');
let isInscrit = document.getElementById('accueil_filtrage_form_isInscrit');
let isPasInscrit = document.getElementById('accueil_filtrage_form_isPasInscrit');
let isPassees = document.getElementById('accueil_filtrage_form_isPassees');

let search = document.getElementById('test');

isOrganisateur.addEventListener('click',inscritsChecked);

function inscritsChecked(){
    if (isOrganisateur.checked) {
        for (let i = 0; i < inscrits.length; i++) {
            if (inscrits[i].innerHTML === '-') {
                noneAll()
            }
        }
    }else {
        for (let i = 0; i < inscrits.length; i++) {
            flexAll();
        }
    }
}
let i = 0;

function flexAll(){
    names[i].style.display = 'flex';
    dates[i].style.display = 'flex';
    clotures[i].style.display = 'flex';
    places[i].style.display = 'flex';
    etats[i].style.display = 'flex';
    inscrits[i].style.display = 'flex';
    organisateurs[i].style.display = 'flex';
    actions[i].style.display = 'flex';
}

function noneAll(){
    names[i].style.display = 'none';
    dates[i].style.display = 'none';
    clotures[i].style.display = 'none';
    places[i].style.display = 'none';
    etats[i].style.display = 'none';
    inscrits[i].style.display = 'none';
    organisateurs[i].style.display = 'none';
    actions[i].style.display = 'none';
}

function searchBar(){
    for (let i = 0; i < names.length; i++) {
        if (names[i].innerHTML.toLowerCase().includes(search.value.toLowerCase())) {
            inscritsChecked()
            flexAll();
        } else if(names[i].innerHTML.toLowerCase() === ''){
            inscritsChecked()

            flexAll()
        }
        else {
            inscritsChecked()

            noneAll()
        }
    }
}
search.addEventListener('input', searchBar)



// function checkOrganisateur(){
//     if (isOrganisateur.checked) {
//         for (let i = 0; i < organisateurs.length; i++) {
//             if (organisateurs[i].innerHTML === '-') {
//                 names[i].style.display = 'none';
//                 dates[i].style.display = 'none';
//                 clotures[i].style.display = 'none';
//                 places[i].style.display = 'none';
//                 etats[i].style.display = 'none';
//                 inscrits[i].style.display = 'none';
//                 organisateurs[i].style.display = 'none';
//                 actions[i].style.display = 'none';
//             }
//         }
//     }
// }
// checkOrganisateur();