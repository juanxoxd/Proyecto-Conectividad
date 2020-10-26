<template>
  <v-container style="padding: 0px ">
    <v-row class="pa-5 align-center">
      <v-col>
        <v-btn
          fab
          large
          dark
          color="blue"
          @click="dialog = true"
        >
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-col>
      <v-col cols="11">
        <h2 class="font-weight-bold text-center">
          Mantenimiento de Medios de Pago
        </h2>
      </v-col>
    </v-row>
    <v-dialog v-model="dialog" persistent scrollable max-width="60vw">
      <v-card>
        <v-card-title class="indigo darken-4">
            <span class="headline" style="color: white">{{ formTitle }}</span>
        </v-card-title>

        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
                <v-col cols="12" sm="4">
                    <v-text-field
                    v-model="CodigoSUNAT"
                    label="Código *"
                    :rules="[fieldRules.required]"
                    prepend-icon="mdi-domain"
                    required
                    ></v-text-field>
                </v-col>
                <v-col cols="12" sm="8">
                    <v-text-field
                    v-model="Nombre"
                    label="Nombre *"
                    :rules="[fieldRules.required]"
                    prepend-icon="mdi-domain"
                    required
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
            @click="(dialog = false), limpiar()"
            >Cancelar</v-btn
          >
          <v-btn
            :loading="saveLoading"
            :disabled="saveLoading"
            color="indigo darken-4"
            class="ma-2 white--text"
            depressed
            @mousedown="validate"
            @click="guardar"
            >Guardar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card>
      <v-row>
        <v-card-title>
            <v-spacer></v-spacer>
        </v-card-title>
      <v-col cols="12" class="pt-0">
        <v-data-table
          :headers="headers"
          :items="mediosDePago"          
          :item-class="itemFilaColor"
        >
          <template v-slot:[`item.index`]="{ item }">
            {{ mediosDePago.indexOf(item) + 1 }}
          </template>
          <template v-slot:[`item.actions`]="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-bind="attrs"
                  v-on="on"
                  class="mr-2"
                  color="blue-grey"
                  @click="leer(item)"
                  >mdi-border-color
                </v-icon>
              </template>
              <span>Editar</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-bind="attrs"
                  v-on="on"
                  class="mr-2"
                  :color="item.Vigencia ? 'red lighten-1' : 'green'"
                  @click="cambiarVigencia(item)"
                  >{{
                    item.Vigencia
                      ? "mdi-close-circle-outline"
                      : "mdi-checkbox-marked-circle-outline"
                  }}</v-icon
                >
              </template>
              <span>{{ item.Vigencia ? "Dar de baja" : "Dar de alta" }}</span>
            </v-tooltip>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
    </v-card>
    
  </v-container>
</template>

<script>
import { get, post, put, patch } from "../api/api";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      edit: false,
      valid: true,
      saveLoading: false,
      dialog: false,
      fieldRules: {
        required: (v) => !!v || "Campo requerido",       
      },
      headers: [
        { 
        text: "Nº", 
        value: "index", 
        width: "10%", 
        class:'light blue darken-4 white--text', 
        sortable: false  
        },
        {
        text: "Nombre",
        align: "start",
        value: "Nombre",
        class:'light blue darken-4 white--text', 
        sortable: false,
        },
        { 
        text: "Código ", 
        value: "CodigoSUNAT", 
        class:'light blue darken-4 white--text', 
        sortable: false, },
        { 
        text: "Acciones", 
        value: "actions",
        width: "20%",
        class:'light blue darken-4 white--text',  
        sortable: false,   
        },
      ],
      options: [
        {
          name: "Editar",
          icon: "mdi-pencil",
          function: this.leer,
        },
        {
          name: "CambiarVigencia",
          icon: "mdi-check-box-outline",
          function: this.cambiarVigencia,
        },
      ],
      mediosDePago: [],
      Nombre: "",
      CodigoSUNAT: "",
      indiceEditar: -1,
    };
  },

  computed: {
    formTitle() {
      return this.indiceEditar === -1 ? "Nuevo Medio de Pago" : "Editar Medio de Pago";
    },
  },

  methods: {
    validate() {
      this.$refs.form.validate();
    },

    limpiar() {
      this.Nombre = "";
      this.CodigoSUNAT = "";
      this.indiceEditar=-1;
      this.$refs.form.resetValidation();
    },
    
    guardar() {
      if (this.indiceEditar === -1) {
        this.registrar();
      } else {
        this.editar();
      }
    },

    getMedioPago() {
      return {
        Nombre: this.Nombre,
        CodigoSUNAT: this.CodigoSUNAT,
      };
    },

    registrar() {
      if (this.valid == false) return;
      this.saveLoading = true;
      post("mediosdepago", this.getMedioPago()).then(() => {
        this.saveLoading = false;
        this.dialog = false;
        this.limpiar();
        Swal.fire({
            position: "top-center",
          title: "Sistema",
          text: "Medio de Pago registrado exitosamente.",
          icon: "success",
          confirmButtonText: "OK",
          timer: 2500,
        });
        this.refrescarMediosDePago();
      })
      .catch(() => {
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "No se registró Medio de Pago",
            icon: "error",
            confirmButtonText: "OK",
            timer: 2500,
          });
          this.saveLoading = false;
        });
    },

    editar() {
      if (this.valid == false) return;
      this.saveLoading = true;
      put("mediosdepago/" + this.codigoEditar, this.getMedioPago())
        .then(() => {
          this.saveLoading = false;
          this.codigoEditar = null;
          this.dialog = false;
          this.limpiar();
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "Medio de Pago actualizado exitosamente.",
            icon: "success",
            confirmButtonText: "OK",
            timer: 2500,
          });
          this.refrescarMediosDePago();
        })
        .catch(() => {
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "No se actualizó Medio de Pago",
            icon: "error",
            confirmButtonText: "OK",
            timer: 2500,
          });
          this.saveLoading = false;
        });
    },

    leer(medioPago) {
      this.indiceEditar = medioPago.Codigo
      this.codigoEditar = medioPago.Codigo;
      this.mostrarMedioPago(medioPago.Codigo).then(() => {
        this.dialog = true;
      });
    },

    async mostrarMedioPago(codigo) {
      const medioPago = await get("mediodepago/" + codigo);
      this.Nombre = medioPago.Nombre;
      this.CodigoSUNAT = medioPago.CodigoSUNAT;
    },

    cambiarVigencia(medioPago) {
      patch("mediosdepago/" + medioPago.Codigo)
        .then((data) => {
            if(data.Vigencia == 1){
                Swal.fire({
                position: "top-center",
                title: "Sistema",
                text: "Medio de Pago dado de alta exitosamente",
                icon: "success",
                confirmButtonText: "Ok",
                timer: 2500,
            });
            }else{
            Swal.fire({
                position: "top-center",
                title: "Sistema",
                text: "Medio de Pago dado de baja exitosamente",
                icon: "success",
                confirmButtonText: "Ok",
                timer: 2500,
            });
            }
            this.refrescarMediosDePago();
        })
        .catch(() => {
            Swal.fire({
              position: "top-center",
              title: "Sistema",
              text: "No se actualizó Medio de Pago",
              icon: "error",
              confirmButtonText: "OK",
              timer: 2500,
            });
            this.saveLoading = false;
        });
    },

    refrescarMediosDePago() {
      get("mediosdepago").then((data) => {
        this.mediosDePago = data;
      });
    },
    

    itemFilaColor: function (item) {
      return item.Vigencia ? "black--text" : "red--text";
    },
  },
  created() {
    this.refrescarMediosDePago();
  },
};
</script>

<style></style>

