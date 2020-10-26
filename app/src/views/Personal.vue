<template>
  <v-container style="padding: 0px ">
        <v-row class="pa-5 align-center">
            <v-col>
                <v-btn fab large dark color="blue " @click="dialogEjemplo = true">
                    <v-icon>mdi-plus</v-icon>
                </v-btn>
            </v-col>
            <v-col cols="11">
                <h2 class="font-weight-bold text-center">Mantenimiento de Personal</h2>
            </v-col>
        </v-row>

        <v-dialog v-model="dialogEjemplo" persistent scrollable max-width="60vw">
            <v-card>
                <v-card-title class="headline indigo darken-4">
                    <span v-if="edit" class="headline" style="color:white;">Editar Personal</span>

                    <span v-else class="headline" style="color:white;">Nuevo Personal</span>
                </v-card-title>

                <v-card-text>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field
                                    v-model="Nombres"
                                    :rules="[fieldRules.required]"
                                    label="Nombres"
                                    type="text"
                                    prepend-icon="mdi-border-color"     
                                >
                                </v-text-field>
                            </v-col>

                            <v-col cols="6">
                                <v-text-field
                                v-model="ApellidoPaterno"
                                :rules="[fieldRules.required]"
                                label="Apellido Paterno"
                                prepend-icon="mdi-border-color"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field
                                v-model="ApellidoMaterno"
                                :rules="[fieldRules.required]"
                                label="Apellido Materno"
                                type="text"
                                prepend-icon="mdi-border-color" 
                                >
                                </v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-combobox
                                v-model="Siglas"
                                :rules="[fieldRules.required]"
                                :items="auxListaSNP"
                                label="Sistema Nacional de Pensiones"
                                prepend-icon="mdi-domain"
                                ></v-combobox>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-combobox
                                v-model="Documento"
                                :items="auxListaDocumento"
                                :rules="[fieldRules.required,fieldRules.validarDP]"
                                type="text"
                                label="Tipo de Documento Personal"
                                prepend-icon="mdi-account-box"
                                >
                                </v-combobox>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field
                                v-model="NumeroDocumento"
                                :rules="[fieldRules.validarCantidadNro]"
                                label="N° Documento"
                                type="number"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field
                                v-model="Direccion"
                                :rules="[fieldRules.required]"
                                label="Dirección"
                                prepend-icon="mdi-domain"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field
                                v-model="Telefono"
                                label="Teléfono"
                                type="number"
                                prepend-icon="mdi-phone"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field
                                v-model="Correo"
                                :rules="[fieldRules.required,fieldRules.email]"
                                label="Correo"
                                prepend-icon="mdi-email"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field
                                v-model="Celular"
                                :rules="[fieldRules.required, fieldRules.numCel ]"
                                label="Celular"
                                type="number"
                                maxlength="9"
                                prepend-icon="mdi-cellphone-iphone"
                                ></v-text-field>
                            </v-col>
                        </v-row>

                    </v-form>
                    <span class="red--text">(*) Campos Obligatorios</span>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                    color="indigo darken-4"
                    text
                    @click="(dialogEjemplo = false, edit = false), limpiar()"
                    >Cancelar</v-btn>
                    <v-btn
                    color="indigo darken-4"
                    class="ma-2 white--text"
                    @click="executeEventClick"
                    >Cuardar
                    </v-btn>
                </v-card-actions>
            </v-card>       
        </v-dialog>

        <v-dialog v-model="dialogEjemplo02" persistent max-width="40vw">
            <v-card>
                <v-alert
                    v-model="alert02"
                    type="error"
                    icon="mdi-cloud-alert"
                    close-text="Close Alert"
                >
                    Ocurrio  un error al Registrar, Verificar los campos ingresados
                </v-alert>
                <v-card-actions>
                     <v-spacer></v-spacer>
                        <v-btn
                        color="indigo darken-4"
                        class="ma-2 white--text"
                        @click="(dialogEjemplo02 = false)"
                        >Cancelar</v-btn>
                </v-card-actions>

            </v-card>
        </v-dialog>
            
        <v-card>
            <v-card-title>
                <v-spacer></v-spacer>
                    <v-row>
                        <v-col cols="3">
                                <v-combobox
                                v-model="bopcion"
                                :rules="[fieldRules.valicbo]"
                                :items="listaOpciones"
                                label="Opciones"
                                prepend-icon="mdi-view-headline"
                                ></v-combobox>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field
                            v-model="txtBuscar"
                            append-icon="mdi-magnify"
                            :rules="[fieldRules.verBuscar]"
                            label="Buscar"
                            single-line
                            hide-details
                            v-on:keyup.enter = "fn_buscarPersonal"
                            ></v-text-field>
                        </v-col>
                    </v-row>

            </v-card-title>

            <v-data-table
                :headers="headers"
                :items="listapersonal01"
            >
            <template v-slot:[`item`]="{ item }">
                <tr v-bind:style="item.Vigencia? '' : 'color: red;'" >
                <td>{{ item.contador }}</td>
                <td>{{ item.ApellidoPaterno }} {{ item.ApellidoMaterno }} {{ item.Nombres }}</td>
                <td>{{ item.Celular }}</td>
                <td>{{ item.Correo }}</td>
                <td>{{ item.Direccion }}</td>
                <td> 
                    <v-tooltip bottom> 
                        <template v-slot:activator="{ on, attrs }">         
                            <v-icon 
                                class="mr-2"  
                                v-bind="attrs"
                                v-on="on"
                                @click="showEditPersonal(item)"
                                color="blue-grey">
                                mdi-border-color
                            </v-icon>
                        </template >
                        <span>Editar</span>
                    </v-tooltip>

                     <v-tooltip bottom> 
                        <template v-slot:activator="{ on, attrs }">
                            <v-icon 
                                class="mr-2"   
                                v-bind="attrs"
                                v-on="on"
                                @click="deletePersonal(item)" 
                                :color="item.Vigencia ? 'red lighten-1' : 'green'" > 
                                {{item.Vigencia ? "mdi-close-circle-outline" : "mdi-checkbox-marked-circle-outline"}}
                            </v-icon>
                        </template >
                        <span>{{ item.Vigencia ? "Dar de baja" : "Dar de alta" }}</span>
                    </v-tooltip>
                </td>
                </tr>
            </template >
            </v-data-table>
        </v-card>

    
  </v-container>
</template>

<script>
import { put, patch, get, post } from "../api/api";
import Swal from "sweetalert2";

export default {
    components: {
        //TablesMostrar: () => import("../components/TablesMostrar"), class: 'orange darken-4 white--text'
    },
    data() {
        return {
            txtBuscar : "",
            search: "",
            edit: false,
            alert: false,
            alert02: true,
            dialogEjemplo : false,
            dialogEjemplo02 : false,
            valid: false,
            editID: null,
            fieldRules: {
                required: (v) => !!v || "Campo requerido",
                email: (v) => {
                const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return pattern.test(v) || "Correo electrónico incorrecto.";
                },
                validarDP: (v) => {
                    this.tipoDoc(v);
                    return true
                },
                validarCantidadNro: (v) => {
                    this.cantidadNroActual = v.length
                    if (this.cantidadNroActual == this.maxCantidaNroDocumento) {
                         return true
                    }else{
                        return "Verificar campo requerido"
                    }
                },
                verBuscar: (v) => {
                    if (v.length > 0) {
                        if (this.bopcion == "Nombres" || this.bopcion == "S.N.P" ) {
                            //this.fn_buscarPersonal(v, this.bopcion)
                            return true
                        }else{
                            this.bopcion = 'Opciones'
                            return false
                        }
                    }else{
                        return false
                    }
                },
                valicbo: (v) => {
                    if (v == "Nombres" || v == "S.N.P") {
                        return true
                    }else{
                        return "seleccionar una opción"
                    }
                },
                numCel: (v) => {
                    if (v.length == 9) {
                        return true
                    }else{
                        return "Verificar campo requerido"
                    }
                }
            },
            headers: [
                { text: "N°",  value: "Codigo", sortable: false, class:'light blue darken-4 white--text' },
                { text: "Nombres", value: "Nombres", sortable: false, class:'light blue darken-4 white--text'},
                { text: "Celular", value: "Celular", sortable: false, class:'light blue darken-4 white--text' },
                { text: "Correo", value: "Correo", sortable: false, class:'light blue darken-4 white--text' },
                { text: "Dirección", value: "Direccion", sortable: false, class:'light blue darken-4 white--text' },

                { text: "Acciones", value: "actions", width: "15%" , class:'light blue darken-4 white--text'},
            ],

            listapersonal01: [],
            Nombres: "",
            ApellidoPaterno: "",
            ApellidoMaterno: "",
            NumeroDocumento: "",
            Direccion: "",
            Telefono : "",
            Correo: "",
            Celular: "",
            Siglas: '',
            Documento: '',
            maxCantidaNroDocumento: 0,
            cantidadNroActual: 0,
            listaDocumento: [],
            auxListaDocumento: [],
            auxListaSNP: [],
            validSNPCod: [],
            auxvalidDoc:[],
            listaOpciones: [
                "Nombres",
                "S.N.P"
            ],
            bopcion: "",
            valor: "",
        }
    },
    created() {
        //this.actualizarPersonal();
        this.listaCboDocumento();
        this.listaCboSNP();
    },
    methods: {
        limpiar(){
            this.Nombres = "",
            this.ApellidoPaterno = "",
            this.ApellidoMaterno = "",
            this.NumeroDocumento = "",
            this.Direccion = "",
            this.Telefono = "",
            this.Correo = "",
            this.Celular = "",
            this.Siglas = "",
            this.Documento = ""

        },
        executeEventClick() {
            if (this.edit === false) {
                this.registerEmpresa();
            } else {
                this.editEmpresa();
            }
        },
        registerEmpresa() {
            let ba = 0
            ba = this.validarFormulario()
            if (ba == 0) {
                this.dialogEjemplo02 = true
            }else{
                let ban = 0
                ban = this.validarSNP()
                if (ban == 0) {
                    this.dialogEjemplo02 = true
                } else {
                    let ban02 = 0
                    ban02 = this.validarDoc()
                    if (ban02 == 0) {
                        this.dialogEjemplo02 = true
                    } else {
                        post("personal", this.assemblePersonal()).then(() => {

                            Swal.fire({
                                position: "top-center",
                                title: "Sistema",
                                text: "Personal registrado !",
                                icon: "success",
                                confirmButtonText: "Ok",
                                timer: 2500,
                            });
                            
                            //this.saveLoading = false;
                            this.dialogEjemplo = false;
                            //this.$refs.personalTable.fetchData();
                            this.limpiar();
                            //this.actualizarPersonal();
                        })
                        .catch(() => {
                        this.saveLoading = false;
                        });
                    }
                }
            }
        },
        assemblePersonal(){
            return {
                Nombres:  this.Nombres,
                ApellidoPaterno: this.ApellidoPaterno,
                ApellidoMaterno: this.ApellidoMaterno,
                Documento: this.Documento,
                NumeroDocumento: this.NumeroDocumento,
                Direccion: this.Direccion,
                Telefono: this.Telefono,
                Correo: this.Correo,
                Celular: this.Celular,
                Siglas: this.Siglas
            }
        },
        validarSNP(){
            //this.actualizarPersonal();
            let va = 0
            for (let i = 0; i < this.auxListaSNP.length; i++) {
                if (this.auxListaSNP[i] == this.Siglas) {
                    //this.Siglas = this.validSNPCod[i];
                    va = 1;
                }
            }

            if ( this.Celular.length < 9 || this.Celular.length > 9 ) {
                va = 0
            }

            return  va
        },
        validarDoc(){
            let va = 0
            let cam1 = 0
            let cam2 = 0

            for (let i = 0; i < this.auxListaDocumento.length; i++) {
                if (this.auxListaDocumento[i] == this.Documento) {
                    //this.Documento = this.auxvalidDoc[i];
                    //va = 1
                    cam1 = 1
                }                
            }

            for (let i = 0; i < this.auxListaSNP.length; i++) {
                if (this.auxListaSNP[i] == this.Siglas) {
                    //this.Siglas = this.validSNPCod[i];
                    //va = 1;
                    cam2 = 1
                }
            }

            if (cam1 == 1 && cam2 == 1) {
                va = 1
                for (let i = 0; i < this.auxListaDocumento.length; i++) {
                    if (this.auxListaDocumento[i] == this.Documento) {
                        this.Documento = this.auxvalidDoc[i];
                        va = 1
                    }                
                }

                for (let i = 0; i < this.auxListaSNP.length; i++) {
                    if (this.auxListaSNP[i] == this.Siglas) {
                        this.Siglas = this.validSNPCod[i];
                        va = 1;
                    }
                }
            } else {
                va = 0
            }
            
            return  va
        },
        validarFormulario(){
            let ban = 0
            if (this.Nombres == "" || this.ApellidoPaterno == "" || this.ApellidoMaterno == "" || this.NumeroDocumento.length != this.maxCantidaNroDocumento || this.Direccion == "" || this.Correo == "" || this.Celular == "") {
                ban = 0
            }else{
                ban = 1
            }
            return ban
        },
        validarFormulario02(){
            let ban = 0
            if (this.Nombres == "" || this.ApellidoPaterno == "" || this.ApellidoMaterno == "" || this.Direccion == "" || this.Correo == "" || this.Celular == "") {
                ban = 0
            }else{
                ban = 1
            }
            return ban
        },
        async tipoDoc(tipo){
            const tipoD = await get("documentopersonalvigentes");
            for (let i = 0; i < tipoD.length; i++) {
                if (tipoD[i].Nombre == tipo) {
                    this.maxCantidaNroDocumento = tipoD[i].CantidadMaxima;
                }
            }
            if (this.edit != true) {
                this.NumeroDocumento = false;
            }
            
           // this.fieldRules.validarCantidadNro: (v) =>{return false};
        },
        showEditPersonal(personal){
            this.edit = true;
            this.editID = personal.Codigo;
            this.mostrarPersonal(personal.Codigo).then(() => {
                this.dialogEjemplo = true;
            });
        },
        async mostrarPersonal(codigo){
            const personal = await get("personal/"+codigo);
            this.Nombres = personal.Nombres;
            this.ApellidoPaterno = personal.ApellidoPaterno;
            this.ApellidoMaterno = personal.ApellidoMaterno;
            this.Siglas = personal.CodigoSistemaPensiones;
            for (let i = 0; i < this.auxListaSNP.length; i++) {
                if (this.validSNPCod[i] == this.Siglas) {
                    this.Siglas = this.auxListaSNP[i];
                }
            }
            this.Documento = personal.CodigoDocumentoPersonal;
            for (let i = 0; i < this.auxListaDocumento.length; i++) {
                if (this.auxvalidDoc[i] == this.Documento) {
                    this.Documento = this.auxListaDocumento[i];
                }                
            }

            this.NumeroDocumento = personal.NumeroDocumento;
            this.Direccion = personal.Direccion;
            this.Telefono = personal.Telefono;
            this.Correo = personal.Correo;
            this.Celular = personal.Celular;
        },
        editEmpresa(){
            let ba = 0
            ba = this.validarFormulario02()
            if (ba == 0) {
                this.dialogEjemplo02 = true
            }else{
                let ban = 0
                ban = this.validarSNP()
                if (ban == 0) {
                    this.alert = true
                } else {
                    let ban02 = 0
                    ban02 = this.validarDoc()
                    if (ban02 == 0) {
                        this.alert = true
                    } else {
                        put("personal/" + this.editID, this.assemblePersonal())
                            .then(() => {

                            Swal.fire({
                                position: "top-center",
                                title: "Sistema",
                                text: "Personal Modificado !",
                                icon: "success",
                                confirmButtonText: "Ok",
                                timer: 2500,
                            });
                            
                            this.dialogEjemplo = false;
                            this.editID = null;
                            //this.actualizarPersonal();
                            this.limpiar();
                            })
                            .catch(() => {
                                this.saveLoading = false;
                            });
                    }
                }
            }
        },
        deletePersonal(personal){
            patch("personal/" + personal.Codigo).then((data) => {

                if(data.Vigencia == 1){
                    Swal.fire({
                        position: "top-center",
                        title: "Sistema",
                        text: "Personal habilitado con éxito",
                        icon: "success",
                        confirmButtonText: "Ok",
                        timer: 2500,
                    });

                }else{
                    Swal.fire({
                        position: "top-center",
                        title: "Sistema",
                        text: "Personal inhailitado con éxito ",
                        icon: "success",
                        confirmButtonText: "Ok",
                        timer: 2500,
                    });
                }
                this.listapersonal01 = [];
                //this.$refs.empresaTable.fetchData();
            })
            .catch(() => {
                this.alert = true;
            });
        },

        listaCboDocumento(){
            get("documentopersonalvigentes").then((data) => {
                this.listaDocumento = data;
                for (let i = 0; i <  this.listaDocumento.length; i++) {
                    this.auxListaDocumento.push(this.listaDocumento[i].Nombre);

                    this.auxvalidDoc.push(this.listaDocumento[i].Codigo); 
                }
            });
        },

        listaCboSNP(){
            get("sistemadepensionesvigentes").then((data) => {
                this.listaDocumento = data;
                for (let i = 0; i <  this.listaDocumento.length; i++) {
                    this.auxListaSNP.push(this.listaDocumento[i].Siglas);

                    this.validSNPCod.push(this.listaDocumento[i].Codigo,);
                }
            });
        },

        fn_buscarPersonal(){
            
            if (this.bopcion == "Nombres" || this.bopcion == "S.N.P" ) {
                if (this.txtBuscar != "") {
                    this.valor = this.txtBuscar;
                    for (let i = 0; i < this.auxListaSNP.length; i++) {
                        if (this.auxListaSNP[i] == this.valor) {
                            this.valor = this.validSNPCod[i];
                        }
                    }
                    post("personal/" +this.valor+ "/"+this.bopcion ).then((data) => {
                        console.log(data)
                        this.listapersonal01 = data;
                    })
                    .catch(() => {
                    this.listapersonal01 = [];
                    });
                }
            }
           
        }
    }
}
</script>