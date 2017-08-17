/**
 * Created by antonincarlin on 15/01/2017.
 */



// Adapter les images

if (window.matchMedia("(min-width: 640px)").matches) {
    /* La largeur minimum de l'affichage est 600 px inclus */
    var height =$('#beer .desc_produit').height();


    $('#beer .desc_produit img').height(height).css({
        'maxWidth' : 'none',
        'width' : 'auto'
    });
}



// TEMPORAIRE POUR PROD  --------------------------

String.prototype.trimToLength = function(m) {
    return (this.length > m)
        ? jQuery.trim(this).substring(0, m).split(" ").slice(0, -1).join(" ") + "..."
        : this;
};

function lol(){
    $('.lol img').each(function () {
        var URL = $(this).attr('src');
        URL = URL.split('/');
        if(URL[2] === "www.samsamcafe.fr") {
            URL[2] = "www.samsamcafe.fr/prod";
            $(this).attr('src', URL.join('/'));
        }
    });
}
var entityMap = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;',
    '/': '&#x2F;',
    '`': '&#x60;',
    '=': '&#x3D;'
};


// AJAX SEARCH CALL -------------------------------

// ESCAPE HTML
function escapeHtml (string) {
    return String(string).replace(/[&<>"'`=\/]/g, function (s) {
        return entityMap[s];
    });
}


(function (global,$,_) {


    var _URL = baseUrl+'api/v1/product/';

    var get_search_results = function(query){
        $('#search').html('');
        var items = [];
        var item ='';
        $.getJSON( _URL+query, function( data ) {
            console.log(data);
            for(key in data){
                if(data.hasOwnProperty(key)){
                    var cat = data[key];
                    if(!cat.vide){
                        switch (key){
                            case 'biere':
                                for(var i = 0; i < cat.length; i++ ){
                                    var beer = cat[i];
                                    item = '<div class="column small-12 medium-6 large-4 end vignette">'+
                                        '                <a href="'+baseUrl+'biere/Beer/produit/' +  beer.biere_id  + '">'+
                                        '                    <div class="produit" >'+
                                        '                        <div class="small-2 medium-12 columns lol">'+
                                        '                            <img src="'+beer.biere_photo+'" alt="' +  beer.biere_nom  + '">'+
                                        '                        </div>'+
                                        '                        <div class="small-10 medium-12 columns">'+
                                        '                            <div class="row header-detail">'+
                                        '                                <div class="small-7 columns nom ' +  beer.biere_type  + '">' +  beer.biere_nom  + '</div>'+
                                        '                                <div class="small-4 columns type"><span class="' +  beer.biere_type  + '">' +  beer.biere_type  + '</span> | ' +  beer.biere_pourcentage  + '%</div>'+
                                        '                            </div>'+
                                        '                            <div class="small-12 columns description"><i>' +  beer.biere_description.trimToLength(140)  + '...</i></div>'+
                                        '                        </div>'+
                                        '                    </div>'+
                                        '                </a>'+
                                        '            </div>';
                                    items.push(item);

                                }

                                break;

                            case 'jeux':
                                for(i = 0; i < cat.length; i++ ){
                                    var jeu = cat[i];

                                    item = '<div class="column vignette small-12 medium-6 large-4 end" data-filter="' +  jeu.jeu_type + ',*' +  jeu.jeu_nb_joueur_min  + ';' +  jeu.jeu_nb_joueur_max  + ',%' +  jeu.jeu_duree  + '">'+
                                        '                    <a href="' +  baseUrl  + 'jeu/Game/produit/' +  jeu.jeu_id  + '">'+
                                        '                    <div class="produit">'+
                                        '                        <div class="small-2 medium-12 columns lol">'+
                                        '                            <img src="' + jeu.jeu_image  + '" alt="' +  jeu.jeu_nom  + '">'+
                                        '                        </div>'+
                                        '                        <div class="small-10 medium-12 columns">'+
                                        '                            <div class="row header-detail">'+
                                        '                                <div class="small-7 columns nom">' +  jeu.jeu_nom  + '</div>'+
                                        '                                <div class="small-4 columns type"><span>' +  jeu.jeu_type  + '</span></div>'+
                                        '                            </div>'+
                                        '                            <div class="small-12 columns description">'+
                                        '                                <i>' +  jeu.jeu_description.trimToLength(140)  + '...</i></div>'+
                                        '                        </div>'+
                                        '                    </div>'+
                                        '                    </a>'+
                                        '                </div>';
                                    items.push(item);

                                }
                                break;

                            case 'actus':
                                for(i = 0; i < cat.length; i++ ) {
                                    var actu = cat[i];
                                    item = '<div class="column vignette small-12 medium-6 large-4 end" data-filter="' +  actu.actu_categorie+ ' ">' +
                                        '                <a href="' +  baseUrl + 'actualite/' +  actu.actu_id + '">' +
                                        '                    <div class="produit">' +
                                        '                        <div class="small-2 medium-12 columns lol">' +
                                        '                            <img src="' +  actu.actu_image + '" alt="' +  actu.actu_nom + ' ">' +
                                        '                        </div>' +
                                        '                        <div class="small-10 medium-12 columns">' +
                                        '                            <div class="row header-detail">' +
                                        '                                <div class="small-7 columns nom">' +  actu.actu_nom + ' </div>' +
                                        '                                <div class="small-4 columns type"><span>' +  actu.actu_date + ' </span></div>' +
                                        '                            </div>' +
                                        '                            <div class="small-12 columns description">' +
                                        '                                <i>' +  String(actu.actu_contenu).replace(/<\/?[^>]+(>|$)/g, "").trimToLength(140) + ' ...</i>' +
                                        '                            </div>' +
                                        '                        </div>' +
                                        '                    </div>' +
                                        '                </a>' +
                                        '            </div>';
                                }

                                items.push(item);
                                break;
                        }

                    }

                }

            }
            $("<div\>", {
                "class" :"row",
                html : '</hr>'+items.join("")+'</hr>'
            }).prependTo("#search");
        });

    };


    $("#mainSearch input").on('keyup', _.debounce(function (e){
        get_search_results(e.target.value)
    },600));


}(window,jQuery,_));

// lol();
