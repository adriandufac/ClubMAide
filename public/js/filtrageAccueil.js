// Récupération des champs de sorties

let names = Array.from(document.querySelectorAll('.name'));
let dates = Array.from(document.querySelectorAll('.date_debut'));
let clotures = Array.from(document.querySelectorAll('.cloture'));
let places = Array.from(document.querySelectorAll('.place'));
let etats = Array.from(document.querySelectorAll('.etat'));
let inscrits = Array.from(document.querySelectorAll('.inscrit'));
let organisateurs = Array.from(document.querySelectorAll('.organisateur'));
let actions = Array.from(document.querySelectorAll('.action'));
let Rechercher = document.getElementById('search');
let utilisateur = document.getElementById('utilisateur');

function getTableau() {
    console.log('ON RECHARGE LE TABLEAU')
    names = Array.from(document.querySelectorAll('.name'));
    dates = Array.from(document.querySelectorAll('.date_debut'));
    clotures = Array.from(document.querySelectorAll('.cloture'));
    places = Array.from(document.querySelectorAll('.place'));
    etats = Array.from(document.querySelectorAll('.etat'));
    inscrits = Array.from(document.querySelectorAll('.inscrit'));
    organisateurs = Array.from(document.querySelectorAll('.organisateur'));
    actions = Array.from(document.querySelectorAll('.action'));
}

// Récupération des checKBox
let isOrganisateur = document.getElementById('accueil_filtrage_form_isOrganisateur');
let isInscrit = document.getElementById('accueil_filtrage_form_isInscrit');
let isPasInscrit = document.getElementById('accueil_filtrage_form_isPasInscrit');
let isPassees = document.getElementById('accueil_filtrage_form_sortiesPassees');

let search = document.getElementById('accueil_filtrage_form_nom_sortie');
let campusBar = document.getElementById('accueil_filtrage_form_campus');
let dateRechercheDebut = document.getElementById('accueil_filtrage_form_date_debut');
let dateRechercheFin = document.getElementById('accueil_filtrage_form_date_fin');


// LISTENERS
Rechercher.addEventListener('click', checkAll)

let tabDesI = [];
let i =0;

// FONCTIONS

let dateFinRecherche;
let dateDebutRecherche;

function checkAll(){ // a n'importe quel event
 // remettre tTOUT ds le tableau
    console.log('OBJET DATE RECHERCHE FIN : ')
    console.log(dateRechercheFin.value)
    dateFinRecherche = new Date(dateRechercheFin.value)
    dateDebutRecherche = new Date(dateRechercheDebut.value)
    console.log(dateDebutRecherche)
    console.log(dateFinRecherche)
    getTableau();
    displayall();
    searchBar();
    checkboxPasse();
    checkboxOrga();
    checkBoxInscrit();
    checkCampus();
    checkDates();
    console.log('event activé');
}
//
function displayall (){
    for (let i = 0; i < names.length; i++) {
        flexAll(i);
    }
}
function searchBar(){
    for (let i = 0; i < names.length; i++) {
        if (names[i].innerHTML.toLowerCase().includes(search.value.toLowerCase())) {
            flexAll(i);
            console.log('ola');
        }else {
            console.log('yoyoyoy')
            noneAll(i)
            //ajoute au tableau des trucs a virer
            tabDesI.push(i);
        }
    }
    console.log('tabDesI :');
    console.log (tabDesI);
    console.log('LE TABLEAU AVANT : ')
    console.log(names);
    //on vire a la fin sinon ca casse notre boucle
    for ( j = tabDesI.length-1;j>=0;j--){
        virerLigne(tabDesI[j]);
    }
    console.log('LE TABLEAU APRES : ')
    console.log(names);
    tabDesI = [];
}

// vérifier quand on peut créer des users
function checkboxPasse(){
    if(isPassees.checked){
        for (let i = 0; i < names.length; i++) {
            if (etats[i].innerHTML.includes('Passée')) {
                flexAll(i);
            }else {
                noneAll(i)
                // virer le truc que tu n'affiches oas
                tabDesI.push(i);
            }
        }
        //ON EST OBLIGE DE SUPPRIMER LES LIGNES DU TABLEAU A LA TTE FIN SINON CA FUCKED UP NOTRE BOUCLE FOR
        console.log('LE TABLEAU AVANT : ')
        console.log(names);
        for ( j = tabDesI.length-1;j>=0;j--){
            virerLigne(tabDesI[j]);
        }
        console.log('LE TABLEAU APRES : ')
        console.log(names);
        //virerLigne(i);
        console.log('tabDesI :');
        console.log (tabDesI);
        tabDesI = [];

    }
}

function checkboxOrga(){
    if(isOrganisateur.checked){
        console.log ('ON RECOIT DANS ORGA ' + names.length + 'elements')
        for (let i = 0; i < names.length; i++) {
            if (organisateurs[i].innerHTML.includes(utilisateur.innerHTML)) {
                flexAll(i);
                console.log(i);
                console.log('on est ds le IF de orga')

            }else {
                console.log('EFFACEMENT ' + i);
                noneAll(i)

                tabDesI.push(i);
                // virer le truc que tu n'affiches oas
                console.log('on est ds le ELSE de orga')
            }
        }
        console.log('tabDesI :');
        console.log (tabDesI);
        console.log('LE TABLEAU AVANT : ')
        console.log(names);
        for ( j = tabDesI.length-1;j>=0;j--){
            virerLigne(tabDesI[j]);
        }
        console.log('LE TABLEAU APRES : ')
        console.log(names);
        //virerLigne(i);

        tabDesI = [];
    }
}

function checkBoxInscrit(){

    if(isInscrit.checked && !isPasInscrit.checked){
        for (let i = 0; i < names.length; i++) {
            if (inscrits[i].innerHTML.includes('X')) {
                flexAll(i);
                console.log(i);
                console.log('on est ds le IF de INSCRIT')

            }else {
                console.log('EFFACEMENT ' + i);
                noneAll(i)

                tabDesI.push(i);
                // virer le truc que tu n'affiches oas
                console.log('on est ds le ELSE de INSCRIT')
            }
        }
        console.log('tabDesI :');
        console.log (tabDesI);
        console.log('LE TABLEAU AVANT : ')
        console.log(names);
        for ( j = tabDesI.length-1;j>=0;j--){
            virerLigne(tabDesI[j]);
        }
        console.log('LE TABLEAU APRES : ')
        console.log(names);
        tabDesI = [];
    }

    if(!isInscrit.checked &&  isPasInscrit.checked){
        for (let i = 0; i < names.length; i++) {
            if (inscrits[i].innerHTML.includes('-')) {
                flexAll(i);
                console.log(i);
                console.log('on est ds le IF de INSCRIT')

            }else {
                console.log('EFFACEMENT ' + i);
                noneAll(i)

                tabDesI.push(i);
                // virer le truc que tu n'affiches oas
                console.log('on est ds le ELSE de INSCRIT')
            }
        }
        console.log('tabDesI :');
        console.log (tabDesI);
        console.log('LE TABLEAU AVANT : ')
        console.log(names);
        for ( j = tabDesI.length-1;j>=0;j--){
            virerLigne(tabDesI[j]);
        }
        console.log('LE TABLEAU APRES : ')
        console.log(names);
        tabDesI = [];
    }
}

function checkCampus(){
    if(campusBar.value != ''){
        console.log('campus BAR NOT NULL')
        for (let i = 0; i < names.length; i++) {
            if(names[i].getAttribute("campus") === campusBar.value){
                flexAll(i);
            }
            else{
                noneAll(i)
                tabDesI.push(i);
            }
        }
        for ( j = tabDesI.length-1;j>=0;j--){
            virerLigne(tabDesI[j]);
        }
        tabDesI = [];
    } else{
        console.log('campus BAR NULL')
        for (let i = 0; i < names.length; i++) {
            flexAll(i)
        }
    }

}

function checkDates(){
    if(dateRechercheDebut.value !== '' && dateRechercheFin.value !== ''){

        console.log('DATES NOT NULL');
        for (let i = 0; i < names.length; i++) {
            console.log('la date qu on crée !')
            let creationDate = (dates[i].innerHTML).split('/')
            creationDatebis = creationDate[2].split(' ')
            let DateString = creationDatebis[0]+'-'+  creationDate[1] +'-'+ creationDate[0]
            console.log(creationDate)
            console.log('dazjfniazeoh')
            console.log(DateString)


            console.log(new Date(DateString))
            console.log ('Date Recherche DEBUT ')
            console.log(dateDebutRecherche)
            console.log ('Date de la sortie ')
            console.log(new Date(DateString))
            console.log ('Date de FIN ')
            console.log(dateFinRecherche)
            if(dateDebutRecherche <= new Date(DateString) && new Date(DateString) <= dateFinRecherche){
                console.log('On passe dans le IF des DATES')

                flexAll(i);
            }
            else{
                console.log('KEBAB')
                noneAll(i)
                tabDesI.push(i);
            }
        }
        for ( j = tabDesI.length-1;j>=0;j--){
            virerLigne(tabDesI[j]);
        }
        tabDesI = [];
    }
}

function virerLigne(i) {

    names.splice(i, 1);
    dates.splice(i, 1);
    clotures.splice(i, 1);
    places.splice(i, 1);
    etats.splice(i, 1);
    inscrits.splice(i, 1);
    organisateurs.splice(i, 1);
    actions.splice(i, 1);

}

// AFFICHAGE
function flexAll(i){
    names[i].style.display = 'flex';
    dates[i].style.display = 'flex';
    clotures[i].style.display = 'flex';
    places[i].style.display = 'flex';
    etats[i].style.display = 'flex';
    inscrits[i].style.display = 'flex';
    organisateurs[i].style.display = 'flex';
    actions[i].style.display = 'flex';
}

function noneAll(i){
    names[i].style.display = 'none';
    dates[i].style.display = 'none';
    clotures[i].style.display = 'none';
    places[i].style.display = 'none';
    etats[i].style.display = 'none';
    inscrits[i].style.display = 'none';
    organisateurs[i].style.display = 'none';
    actions[i].style.display = 'none';
}
