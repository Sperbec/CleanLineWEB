export class search {
    constructor(myurlp, mysearchp, ul_add_lip) {
        this.url = myurlp;
        this.mysearch = mysearchp;
        this.ul_add_li = ul_add_lip;
        this.idli = "mylist";
        this.pcantidad = document.querySelector("#pcantidad");
    }

    InputSearch() {
        this.mysearch.addEventListener("input", (e) => {
            e.preventDefault();
            try {
                let token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
                let minimo_letras = 1;
                let valor = this.mysearch.value;
                console.log(valor);


                if (valor.length > minimo_letras) {
                    let datasearh = new FormData();
                    datasearh.append("valor", valor);
                    fetch(this.url, {
                            headers: {
                                "X-CSRF-TOKEN": token,
                            },
                            method: "post",
                            body: datasearh,
                        })
                        .then((data) => data.json())
                        .then((data) => {
                            console.log("Success:", data);
                            this.Showlist(data, valor);
                        })
                        .catch(function (error) {
                            console.error("Error:", error);
                        });
                } else {
                    this.ul_add_li.style.display = "none";
                }


            } catch (error) {

            }
        });
    }

    Showlist(data, valor) {
        this.ul_add_li.style.display = "block";

        if (data.result != "") {
            let arrayp = data.result;
            this.ul_add_li.innerHTML = "";
            let n = 0;

            this.Show_list_each_data(arrayp, valor, n);

            let adclasli = document.getElementById('1' + this.idli);
            /* adclasli.classList.add('selected'); */
        } else {
            this.ul_add_li.innerHTML = "";
            this.ul_add_li.innerHTML += `
                        <p style="color:red;"><br>No se encontro</p>
                    `;
        }

    }

    Show_list_each_data(arrayp, valor, n) {
        for (let item of arrayp) {
            n++;
            let nombre = item.nombre;
            var cantidad = item.cantidad_existencia;

            var path = $(location).attr('pathname');

           
                if (cantidad > 0) {
                    n++;

                    this.ul_add_li.innerHTML += `

                <div class="resultado_search" >
                    
                        
                        <div class="container1">
                        
                            <li id="${n+this.idli}" value="${item.nombre}" class="list-group-item drop-item">
                            
                            <a href="../../frontend/detalle/${item.id_producto}"><img class="img-search" src="http://cleanadsi.com/api/get-img?path=${item.imagen}" width="50" height="50"><strong>${nombre.substr(0,valor.length)}</strong>${nombre.substr(valor.length)}
                            Precio del producto: $ ${item.precio}</a>
                            
                            </li>
                            
                        </div>
                    
                        
                    
                </div>
            `;

                    var mylist = arrayp;

                    console.log(mylist);


                }
                else{
                    this.ul_add_li.innerHTML = "";
                    this.ul_add_li.innerHTML += `
                        <p style="color:red;"><br>No se encontro</p>
                    `;
                }
            

        }
    }


}
