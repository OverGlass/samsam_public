/**
 * Created by antonincarlin on 05/01/2017.
 */
// LIBRARY FILTRE
(function($, global){
    String.prototype.truncate = String.prototype.truncate ||
        function (n){
            return this.slice(0,n);
        };

    var OverFilter = function(array,item,pageName) {
        return new OverFilter.init(array,item,pageName);
    };


    // Créer l'objet OverFilter
    // Avec comme attribut des selecteurs CSS dans une array
    // Transforme les attributs de la fonction en attribut de l'obj "OverFilter"
    // égale à des objs jQuery créer à la volé

    OverFilter.init = function(array,item,pageName) {
        var self = this;
        for(var i = 0; i < array.length; i++){
            self['$'+array[i]] = $('#'+ array[i]);
        }
        self.item = item;
        self.pageName = pageName;
    };



    OverFilter.prototype = {

        dataToFilter : {},

        filter : function(){

            var self = this;

            var $item = $('.'+self.item);
            //Convertion en tableau de dataTofilter
            var arrData = Object.keys(self.dataToFilter).map(function (key) { return self.dataToFilter[key]}).sort();
            // console.log(arrData);

            $item.each(function () {
                var dataFilter = $(this).attr('data-filter');
                if (dataFilter) {
                    dataFilter = dataFilter.split(',').sort();
                }
                var countTrue = 0;
                var countDefault = 0;

                for(var i = 0; i < arrData.length; i++){
                    for (var y =0; y < dataFilter.length; y++) {

                        if(arrData[i] === dataFilter[y]){
                            // console.log(arrData[i], dataFilter[y]);
                            countTrue++;

                        } else if(arrData[i][0] === "%" && dataFilter[y][0] === "%" && arrData[i] != dataFilter[y]){

                            var value = Number(arrData[i].substring(1));
                            var value2 = Number(dataFilter[y].substring(1));
                                if(value > value2){
                                    countTrue++;
                                }

                        } else if (arrData[i][0] === "*" && dataFilter[y][0] === "*"){
                            var data = Number(arrData[i].substring(1));
                            var data2 = dataFilter[y].substring(1).split(';');
                            if(data >= data2[0]-0 && data <= data2[1]-0){
                                countTrue++;
                            }

                        }

                    }
                    if(arrData[i] ===  'default' || arrData[i] ===  '*NaN' || arrData[i] ===  '%NaN'){
                        countDefault++;
                    }


                }

                if(arrData.length - countDefault != countTrue) {
                    $(this).fadeOut('fast');
                } else {
                    $(this).fadeIn('fast');
                }

                // Si il ya deux argument, il doit y avoir deux true.


            });


        },

        isJqueryObj : function(attr){
            var self = this;

            // return l'attribut si === obj jQuery
            if(self[attr] instanceof $){

                return attr;
            }
        },

        saveParam : function () {
            var self = this;
            var $item = $('.'+self.item);
            $item.each(function () {
               $(this).click(function (e) {
                   e.preventDefault();
                   localStorage.setItem('saveDataToFilter'+self.pageName, JSON.stringify(self.dataToFilter));

                   window.location.href = $('a',this).attr('href');
               })
            });
        },

        reset : function () {

            var self = this;
            self.dataToFilter = {};
            $('input').prop("checked",false);
            $('select').prop('selectedIndex',0);
            self.filter();
        },

        listener : function() {
            var self = this;
            // On trie les attributs de OverFilter
            // Et on place les attributs 'Event"
            // dans self.attrForEvent

            $('#resetFilter').click(function () {
                self.reset();
            });


            for (key in self){
                if (self.hasOwnProperty(key)) {
                    if(self[this.isJqueryObj(key)]){
                        var attrs = this.isJqueryObj(key);
                    }


                    // console.log('--------------v');
                    // console.log(attrs);

                    // On trie par nom de tag
                    var tagName = this[attrs].prop('tagName');



                    // SI SELECT
                    if (tagName == 'SELECT') {
                        self[attrs].change(function () {
                            var selector= this.selector;
                            self.dataToFilter[selector.substring(1)] = this.val();
                            self.filter();

                        }.bind(self[attrs]));
                        //On bind pour que l'objetEvent garde son propre contexte
                        // Et évite une closure.
                    }

                    if (tagName == 'A') {
                        self[attrs].click(function (e) {
                            e.preventDefault();

                        }.bind(self[attrs]));
                    }

                    if (tagName == 'INPUT') {
                        if(self[attrs].attr('type') === 'radio') {

                            self[attrs].click(function () {
                                var name = this.attr('name');
                                self.dataToFilter[name] = $('input[name=' + name + ']:checked').val();
                                self.filter();
                            }.bind(self[attrs]));

                        } else {

                            self[attrs].on("change keyup", function () {
                                var selector= this.selector;
                                var value = this.val();
                                if( value === ""){
                                    self.dataToFilter[selector.substring(1)] = "default";
                                    self.filter();

                                }else if(this.attr("data-option") === "between"){
                                    self.dataToFilter[selector.substring(1)] = '*'+value;
                                    self.filter();

                                } else if(this.attr("data-option") === "range") {
                                    self.dataToFilter[selector.substring(1)] = '%'+value;
                                    self.filter();
                                }


                            }.bind(self[attrs]));
                        }

                    }

                    if(localStorage.getItem('saveDataToFilter'+self.pageName)){
                        self.perso();
                        self.dataToFilter = JSON.parse(localStorage.getItem('saveDataToFilter'+self.pageName));
                        $('#exampleSwitch').prop('checked',true).change();
                        localStorage.removeItem('saveDataToFilter'+self.pageName);

                        for(key2 in self.dataToFilter){
                            if(self.dataToFilter.hasOwnProperty(key2)){
                                var $Obj = $('#'+key2);
                                var value =self.dataToFilter[key2];
                                if(value[0] == '%' || value[0] == '*'){
                                    value = value.substring(1);
                                }
                                // switch ($Obj.selector){
                                //     case "SELECT":
                                //         $Obj
                                // }

                                // if($Obj.selector

                                if($('#'+value).attr('type') === 'radio') {
                                    $('#'+value).prop('checked',true);
                                }

                                $Obj.val(value);



                                //PERSONNALISE
                                $('#slider-time , #slider-joueur').each(function () {
                                    var selector= '#'+ $(this).children('.slider-handle').attr('aria-controls');
                                    console.log(key2, selector);
                                    if(selector == '#'+key2){
                                        $(this).attr('data-initial-start', value);
                                    }
                                })
                            }
                        }
                        self.filter();
                    }
                }

            }

        },

        perso : function () {

            var self = this;


            var $time = $('#slider-time');

            var $joueur = $('#slider-joueur');

                var selector = '#' + $time.children('.slider-handle').attr('aria-controls');
                self.dataToFilter[selector.substring(1)] = "%" + $time.children('.slider-handle').attr('aria-valuenow');
                self.filter();


                var selector2 = '#' + $joueur.children('.slider-handle').attr('aria-controls');
                console.log(selector2);
                self.dataToFilter[selector2.substring(1)] = "*" + $joueur.children('.slider-handle').attr('aria-valuenow');
                self.filter();


                // PERSONNALISER FOUNDATION
                $time.on('changed.zf.slider', function () {
                    var selector = '#' + $(this).children('.slider-handle').attr('aria-controls');
                    self.dataToFilter[selector.substring(1)] = "%" + $(this).children('.slider-handle').attr('aria-valuenow');
                    self.filter();
                });

                $joueur.on('changed.zf.slider', function () {
                    var selector = '#' + $(this).children('.slider-handle').attr('aria-controls');
                    console.log(selector);
                    self.dataToFilter[selector.substring(1)] = "*" + $(this).children('.slider-handle').attr('aria-valuenow');
                    self.filter();
                });
        }
    };

    OverFilter.init.prototype = OverFilter.prototype;

    global.OverFilter = global.OvF = OverFilter;



})(jQuery, window);

