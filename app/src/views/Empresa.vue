<template>
  <v-container style="padding: 0px ">
    <v-row class="pa-5 align-center">
      <v-col>
        <v-btn
          fab
          large
          dark
          color="blue"
          @click="dialogEjemplo = true"
        >
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-col>
      <v-col cols="11">
        <h2 class="font-weight-bold text-center">
          Mantenimiento de Empresa
        </h2>
      </v-col>
    </v-row>
    <v-dialog v-model="dialogEjemplo" persistent scrollable max-width="60vw">
      <v-card>
        <v-card-title class="headline indigo darken-4">
          <span v-if="edit" class="headline" style="color: white"
            >Editar Empresa</span
          >
          <span v-else class="headline" style="color: white"
            >Nueva Empresa</span
          >
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="RazonSocial"
                  :rules="[fieldRules.required]"
                  label="Razón Social *"
                  prepend-icon="mdi-domain"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="RUC"
                  :rules="[fieldRules.required, fieldRules.validateRuc]"
                  label="RUC *"
                  type="number"
                  maxlength="11"
                  prepend-icon="mdi-domain"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="Facebook"
                  label="Facebook"
                  prepend-icon="mdi-facebook-box"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="Instagram"
                  label="Instagram"
                  prepend-icon="mdi-instagram"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="Youtube"
                  label="Youtube"
                  prepend-icon="mdi-youtube"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="Whatsapp"
                  label="Whatsapp"
                  prepend-icon="mdi-whatsapp"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="Correo"
                  :rules="[fieldRules.required, fieldRules.email]"
                  label="Correo *"
                  prepend-icon="mdi-email"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-file-input
                  v-model="Logo"
                  label="Logo"
                  prepend-icon="mdi-camera"
                  accept="image/*"
                ></v-file-input>
              </v-col>
              <!-- <v-col v-else cols="12">
                <v-img :src="Logo"></v-img>
              </v-col> -->
            </v-row>
          </v-form>
          <span class="red--text">(*) Campos Obligatorios</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="indigo darken-4"
            text
            @click="(dialogEjemplo = false), limpiar()"
            >Cancelar</v-btn
          >
          <v-btn
            :loading="saveLoading"
            :disabled="saveLoading"
            color="indigo darken-4"
            class="ma-2 white--text"
            depressed
            @mousedown="validate"
            @click="executeEventClick"
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
          :items="empresas"
          :search="search"
          :item-class="itemFilaColor"
        >
          <template v-slot:[`item.index`]="{ item }">
            {{ empresas.indexOf(item) + 1 }}
          </template>
          <template v-slot:[`item.actions`]="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-bind="attrs"
                  v-on="on"
                  class="mr-2"
                  color="blue-grey"
                  @click="showEditEmpresa(item)"
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
                  @click="deleteEmpresa(item)"
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
import { get, enviarConArchivos, patch } from "../api/api";
// import { logos } from "../api/config";
import Swal from "sweetalert2";
export default {
  data() {
    return {
      edit: false,
      alert: false,
      valid: true,
      saveLoading: false,
      dialogEjemplo: false,
      fieldRules: {
        required: (v) => !!v || "Campo requerido",
        email: (v) => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(v) || "Correo electrónico incorrecto.";
        },
        validateRuc: (v) => v.length == 11 || "RUC incorrecto.",
      },
      headers: [
        { text: "Nº", value: "index", width: "10%", class:'light blue darken-4 white--text' },
        {
          text: "RUC",
          align: "start",
          sortable: false,
          value: "RUC",
          width: "20%", class:'light blue darken-4 white--text'
        },
        { text: "Razon Social", value: "RazonSocial", width: "25%", class:'light blue darken-4 white--text' },
        { text: "Correo", value: "Correo", width: "30%", class:'light blue darken-4 white--text' },
        { text: "Acciones", value: "actions", width: "15%", class:'light blue darken-4 white--text' },
      ],
      options: [
        {
          name: "Editar",
          icon: "mdi-pencil",
          function: this.showEditEmpresa,
        },
        {
          name: "Cambiar vigencia",
          icon: "mdi-check-box-outline",
          function: this.deleteEmpresa,
        },
      ],
      empresas: [],
      search: "",
      RUC: "",
      RazonSocial: "",
      Facebook: "",
      Instagram: "",
      Youtube: "",
      Whatsapp: "",
      Correo: "",
      Logo: null,
    };
  },
  methods: {
    validate() {
      this.$refs.form.validate();
    },
    limpiar() {
      this.RazonSocial = "";
      this.RUC = "";
      this.Instagram = "";
      this.Facebook = "";
      this.Youtube = "";
      this.Whatsapp = "";
      this.Correo = "";
      this.Logo = null;
      this.edit = false;
      this.$refs.form.resetValidation();
    },
    executeEventClick() {
      if (this.edit === false) {
        this.registerEmpresa();
      } else {
        this.editEmpresa();
      }
    },
    itemFilaColor: function (item) {
      return item.Vigencia ? "black--text" : "red--text";
    },
    assembleEmpresa() {
      let form = new FormData();
      form.append("RazonSocial", this.RazonSocial);
      form.append("RUC", this.RUC);
      form.append("Facebook", this.Facebook);
      form.append("Instagram", this.Instagram);
      form.append("Youtube", this.Youtube);
      form.append("Whatsapp", this.Whatsapp);
      form.append("Correo", this.Correo);
      form.append("Logo", this.Logo);
      return form;
    },
    registerEmpresa() {
      if (this.valid == false) return;
      this.saveLoading = true;
      enviarConArchivos("empresas", this.assembleEmpresa()).then(() => {
        this.saveLoading = false;
        this.dialogEjemplo = false;
        this.limpiar();
        Swal.fire({
          title: "Sistema",
          text: "Empresa registrada correctamente.",
          icon: "success",
          confirmButtonText: "OK",
        });
        this.actualizarEmpresas();
      });
    },
    editEmpresa() {
      if (this.valid == false) return;
      this.saveLoading = true;
      enviarConArchivos("empresas/" + this.editId, this.assembleEmpresa())
        .then(() => {
          this.saveLoading = false;
          this.editId = null;
          this.dialogEjemplo = false;
          // this.$refs.empresaTable.fetchData();
          this.limpiar();
          Swal.fire({
            title: "Sistema",
            text: "Empresa actualizada correctamente.",
            icon: "success",
            confirmButtonText: "OK",
          });
          this.actualizarEmpresas();
        })
        .catch(() => {
          this.alert = true;
        });
    },
    showEditEmpresa(empresa) {
      this.edit = true;
      this.editId = empresa.Codigo;
      this.mostrarEmpresa(empresa.Codigo).then(() => {
        this.dialogEjemplo = true;
      });
    },
    deleteEmpresa(empresa) {
      patch("empresa/" + empresa.Codigo)
        .then(() => {
          Swal.fire({
            title: "Sistema",
            text: "Empresa actualizada correctamente.",
            icon: "success",
            confirmButtonText: "OK",
          });
          this.actualizarEmpresas();
        })
        .catch(() => {});
    },
    actualizarEmpresas() {
      get("empresas").then((data) => {
        this.empresas = data;
      });
    },
    async mostrarEmpresa(codigo) {
      const empresa = await get("empresas/" + codigo);
      this.RazonSocial = empresa.RazonSocial;
      this.RUC = empresa.RUC;
      this.Facebook = empresa.Facbook;
      this.Instagram = empresa.Instagram;
      this.Youtube = empresa.Youtube;
      this.Whatsapp = empresa.Whatsapp;
      this.Correo = empresa.Correo;
      // this.Logo = this.ver ? logos + empresa.Logo : null;
    },
  },
  created() {
    this.actualizarEmpresas();
  },
};
</script>

<style></style>