<template>
    <v-content style="padding: 0px ">
        <v-row class="pa-5 align-center">
            <v-col>
                <v-btn fab lange dark color="blue darken-3" @click="dialogEjemplo = true">
                    <v-icon>mdi-plus</v-icon>
                </v-btn>
            </v-col>
            <v-col cols="11">
                <h2 class="font-weight-regular text-center">Mantenimiento de Documento Personal</h2>
            </v-col>
        </v-row>

        <v-dialog v-model="dialogEjemplo" persistent scrollable max-width="60vw">
            <v-card>
                <v-card-title  class="headline indigo darken-4">
                    <span v-if="edit" class="headline" style="color: white"
                        >Editar Documento Personal</span
                    >
                    <span v-else class="headline" style="color: white"
                        >Nuevo Documento Personal</span
                    >
                </v-card-title>
                <v-card-text>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field
                                    v-model="Nombre"
                                    :rules="[fieldRules.required]"
                                    label="Nombre"
                                    type="text"
                                    prepend-icon="mdi-border-color"     
                                >
                                </v-text-field>
                            </v-col>

                            <v-col cols="6">
                                <v-text-field
                                v-model="CodigoSUNAT"
                                :rules="[fieldRules.required]"
                                label="Código SUNAT"
                                prepend-icon="mdi-border-color"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field
                                    v-model="CantidadMinima"
                                    :rules="[fieldRules.required]"
                                    label="Cantidad Mínima de caracteres"
                                    type="number"
                                    prepend-icon="mdi-border-color"     
                                >
                                </v-text-field>
                            </v-col>

                            <v-col cols="6">
                                <v-text-field
                                v-model="CantidadMaxima"
                                :rules="[fieldRules.required]"
                                label="Cantidad Máxima de caracteres"
                                type="number"
                                prepend-icon="mdi-border-color"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-combobox
                                v-model="Tipo"
                                :items="listadoTipo"
                                :rules="[fieldRules.required]"
                                type="text"
                                label="Tipo caracteres"
                                prepend-icon="mdi-account-box"
                                >
                                </v-combobox>
                            </v-col>

                        </v-row>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                    color="indigo darken-4"
                    class="ma-2 white--text"
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

        <v-card class="align-center">
            <v-row >
                <v-col cols="2" >
                </v-col>
                <v-col cols="9" >
                
                       <v-data-table
                            :headers="headers"
                            :items="ListarDocumentoPersonal"
                        >
                        <template v-slot:[`item`]="{ item }">
                            <tr v-bind:style="item.Vigencia? '' : 'color: red; font-weight: bold;'" >
                            <td>{{ item.contador }}</td>
                            <td>{{ item.Nombre }}</td>
                            <td>{{ item.CodigoSUNAT }}</td>
                            <td>          
                                <v-icon class="mr-2"  @click="showEditarDocumento(item)" title="Editar">mdi-border-color</v-icon>
                                <v-icon class="mr-2"   @click="deletePersonal(item)" v-bind:title="item.Vigencia? 'Dar de baja' : 'Dar de alta'"  v-bind:style="item.Vigencia? 'color: #FC4237' : 'color: #0EC244; font-weight: bold;'"> 
                                    {{item.Vigencia ? "mdi-close-circle-outline": "mdi-checkbox-marked-circle-outline"}}
                                </v-icon>
                            </td>
                            </tr>
                        </template >
                        </v-data-table>

                </v-col>
            </v-row>

     
        </v-card>

    </v-content>
</template>


<script>

import { get, post, patch, put } from "../api/api";
import { Toast } from "../plugins/toast";
import Swal from "sweetalert2";

export default {
    components: {
        //TablesMostrar: () => import("../components/TablesMostrar"),
    },
    data() {
        return {
            edit : false,
            editID : null,
            dialogEjemplo: false,
            dialogEjemplo02: false,
            valid: false,
            alert02: true,
            ListarDocumentoPersonal: [],
            fieldRules: {
                required: (v) => !!v || "Campo requerido",
            },
            headers: [
                { text: "N°", sortable: false, value: "contador" , class:'light blue darken-4 white--text'},
                { text: "Nombre", sortable: false, value: "Nombre" , class:'light blue darken-4 white--text'},
                { text: "CodigoSUNAT", sortable: false, value: "CodigoSUNAT" , class:'light blue darken-4 white--text'},

                { text: "Acciones", value: "actions" , class:'light blue darken-4 white--text'},
            ],
            Nombre : "",
            CodigoSUNAT: "",
            CantidadMinima : "",
            CantidadMaxima : "",
            Tipo : "",
            listadoTipo : [
                'Sólo Dígitos',
                'Sólo Letras',
                'Alfanumérico'
            ],
        }
    },
    created() {
        this.actualizarListado();
    },
    methods: {
        limpiar(){
            this.Nombre = "",
            this.CodigoSUNAT = "",
            this.CantidadMinima = "",
            this.CantidadMaxima = "",
            this.Tipo = ""
        },
        actualizarListado() {
            get("documentopersonal").then((data) => {
                this.ListarDocumentoPersonal = data;
            });
        },
        executeEventClick() {
            if (this.edit === false) {
                this.registerDocumentoPersonal();
            } else {
                this.EditarDocumento();
            }
        },
        registerDocumentoPersonal(){
            let ban = 0
            ban = this.validarFormulario()
            if (ban == 0) {
                this.dialogEjemplo02 = true
            } else {
                let ban01 = 0;
                ban01 = this.validarTipo();

                if (ban01 == 0) {
                    this.alert = true;
                } else {
                    post("documentopersonal", this.assembleDocumento()).then(() => {

                        Swal.fire({
                            position: "top-center",
                            title: "Sistema",
                            text: "Documento Personal registrada !",
                            icon: "success",
                            confirmButtonText: "Ok",
                            timer: 2500,
                        });

                        this.dialogEjemplo = false;
                        this.limpiar();
                        this.actualizarListado();
                    })
                    .catch(() => {
                    Toast.fire("No se registró Documento Personal ", "", "error");
                    this.saveLoading = false;
                    });
                }
            }
            
        },
        assembleDocumento(){
            /*let form = new FormData();
            form.append("Nombre", this.Nombre);
            form.append("CodigoSUNAT", this.CodigoSUNAT);
            form.append("CantidadMinima", this.CantidadMinima);
            form.append("CantidadMaxima", this.CantidadMaxima);
            form.append("Tipo", this.Tipo);
            return form;*/
            return {
                Nombre: this.Nombre,
                CodigoSUNAT:  this.CodigoSUNAT,
                CantidadMinima: this.CantidadMinima,
                CantidadMaxima: this.CantidadMaxima,
                Tipo: this.Tipo,
            } 

        },
        validarTipo(){
            let ban = 0
            if (this.Tipo == "Sólo Dígitos" || this.Tipo == "Sólo Letras" || this.Tipo == "Alfanumérico") {
               
                if (this.Tipo == "Sólo Dígitos") {
                    this.Tipo = 'D';
                }
                if (this.Tipo == "Sólo Letras") {
                    this.Tipo = 'L';
                }
                if (this.Tipo == "Alfanumérico") {
                    this.Tipo = 'A';
                }
                ban = 1
            }else{
                ban = 0
            }
            return ban
        },
        validarFormulario(){
            let pos = 0;
            if (this.Nombre == "" || this.CodigoSUNAT == "" || this.CantidadMinima == "" || this.CantidadMaxima == "" || this.listadoTipo == "") {
                pos = 0;
            }else{
                pos = 1;
            }
            return pos;
        },
        //Editar
        showEditarDocumento(doc){
            this.edit = true;
            this.editID = doc.Codigo;
            this.mostrarDocumento(doc.Codigo).then(() => {
                this.dialogEjemplo = true;
            });
        },
        async mostrarDocumento(codigo){
            const documento = await get("documentopersonal/"+codigo);
            this.Nombre = documento.Nombre;
            this.CodigoSUNAT = documento.CodigoSUNAT;
            
            if (documento.Tipo == "D") {
                this.Tipo = 'Sólo Dígitos';
            }
            if (documento.Tipo == "L") {
                this.Tipo = 'Sólo Letras';
            }
            if (documento.Tipo == "A") {
                this.Tipo = 'Alfanumérico';
            }

            this.CantidadMaxima = documento.CantidadMaxima;
            this.CantidadMinima = documento.CantidadMinima;
        },
        EditarDocumento(){
            let ban = 0
            ban = this.validarFormulario()
            if (ban == 0) {
                this.dialogEjemplo02 = true
            } else {
                let ban01 = 0;
                ban01 = this.validarTipo();
                if (ban01 == 0) {
                    this.dialogEjemplo02 = true
                } else {
                     put("documentopersonal/" + this.editID, this.assembleDocumento())
                    .then(() => {
                        this.dialogEjemplo = false;
                        this.editID = null;
                        this.actualizarListado();
                        //this.$refs.personalTable.fetchData();
                        this.limpiar();
                    })
                    .catch(() => {
                        this.alert = true;
                    });
                }
            }
        },
        deletePersonal(doc){
            patch("documentopersonal/" + doc.Codigo).then((data) => {

                if(data.Vigencia == 1){
                    Swal.fire({
                        position: "top-center",
                        title: "Sistema",
                        text: "Documento Personal habilitado con éxito",
                        icon: "success",
                        confirmButtonText: "Ok",
                        timer: 2500,
                    });
                }else{
                    Swal.fire({
                        position: "top-center",
                        title: "Sistema",
                        text: "Documento Personal inhailitado con éxito ",
                        icon: "success",
                        confirmButtonText: "Ok",
                        timer: 2500,
                    });
                }
                
                //this.$refs.empresaTable.fetchData();
                this.actualizarListado();
            })
        },

    },
}
</script>