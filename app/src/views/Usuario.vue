<template>
  <v-container style="padding: 0px ">
    <v-row class="pa-5 align-center">
      <v-col>
        <v-btn fab large dark color="blue" @click="abrirDialog()">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-col>
      <v-col cols="11">
        <h2 class="font-weight-bold text-center">
          Mantenimiento de Usuario
        </h2>
      </v-col>
    </v-row>
    <v-dialog v-model="dialogEjemplo" persistent scrollable max-width="60vw">
      <v-card>
        <v-card-title class="headline indigo darken-4">
          <span v-if="edit" class="headline" style="color: white"
            >Editar Usuario</span
          >
          <span v-else class="headline" style="color: white"
            >Nuevo Usuario</span
          >
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col :cols="edit ? '12' : '6'">
                <v-text-field
                  v-model="Nombre"
                  :rules="[fieldRules.required]"
                  label="Nombre"
                  prepend-icon="mdi-domain"
                  required
                ></v-text-field>
              </v-col>
              <v-col v-if="!edit" cols="6" class="pt-7">
                <v-select
                  :items="itemsPersonal"
                  v-model="CodigoPersonal"
                  label="Personal"
                  prepend-icon="mdi-account"
                  :rules="[fieldRules.required]"
                  dense
                ></v-select>
              </v-col>
            </v-row>
            <v-row>
              <v-col v-if="!edit" cols="6">
                <v-select
                  :items="itemsLocal"
                  v-model="CodigoLocal"
                  label="Local"
                  prepend-icon="mdi-home"
                  dense
                  :rules="[fieldRules.required]"
                ></v-select>
              </v-col>
              <v-col v-if="!edit" cols="6">
                <v-select
                  :items="optionsPerfil"
                  v-model="CodigoPerfil"
                  label="Perfil"
                  prepend-icon="mdi-tag"
                  dense
                  :rules="[fieldRules.required]"
                ></v-select>
              </v-col>
            </v-row>
            <v-row v-if="!edit">
              <v-col cols="6">
                <v-text-field
                  :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show1 ? 'text' : 'password'"
                  v-model="Clave"
                  :rules="[fieldRules.required, fieldRules.validarClave]"
                  label="Clave"
                  maxlength="10"
                  prepend-icon="mdi-domain"
                  @click:append="show1 = !show1"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show1 ? 'text' : 'password'"
                  v-model="ConfirmarClave"
                  :rules="[
                    fieldRules.required,
                    fieldRules.validarClave,
                    confirmarClave,
                  ]"
                  label="Confirmar clave"
                  maxlength="10"
                  prepend-icon="mdi-domain"
                  @click:append="show1 = !show1"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row v-else>
              <v-col cols="6">
                <v-text-field
                  :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show1 ? 'text' : 'password'"
                  v-model="ComprobarClave"
                  :rules="[fieldRules.validarClave]"
                  :error-messages="errors"
                  label="Clave actual"
                  maxlength="10"
                  prepend-icon="mdi-domain"
                  @mouseup="limpiarError()"
                  @click:append="show1 = !show1"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show1 ? 'text' : 'password'"
                  v-model="ClaveNueva"
                  :rules="[fieldRules.validarClave]"
                  label="Clave nueva"
                  maxlength="10"
                  prepend-icon="mdi-domain"
                  @click:append="show1 = !show1"
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
    <v-card class="align-center">
      <v-row>
        <v-card-title>
                    <v-spacer></v-spacer>
                </v-card-title>
      <v-col cols="12" class="pt-0">
        <v-data-table
          :headers="headers"
          :items="usuarios"
          :search="search"
          :item-class="itemFilaColor"
        >
          <template v-slot:[`item.index`]="{ item }">
            {{ usuarios.indexOf(item) + 1 }}
          </template>
          <template v-slot:[`item.actions`]="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-bind="attrs"
                  v-on="on"
                  color="blue-grey"
                  class="mr-2"
                  @click="showEditUsuario(item)"
                >
                  mdi-border-color</v-icon
                >
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
                  @click="deleteUsuario(item)"
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
import { get, post, patch, put } from "../api/api";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      show1: false,
      show2: true,
      edit: false,
      alert: false,
      valid: true,
      saveLoading: false,
      dialogEjemplo: false,
      optionsPerfil: [],
      itemsLocal: [],
      itemsPersonal: [],
      confirmar: true,
      comprobar: true,
      fieldRules: {
        required: (v) => !!v || "Campo requerido",
        validarClave: (v) => v.length <= 10 || "Clave incorrecta",
      },
      headers: [
        {
          text: "N°",
          value: "index",
          width: "10%",
          class:'light blue darken-4 white--text'
        },
        {
          text: "Nombre",
          align: "start",
          sortable: false,
          value: "Nombre",
          width: "20%",
          class:'light blue darken-4 white--text'
        },
        { text: "Perfil", value: "Perfil", width: "30%",class:'light blue darken-4 white--text' },
        { text: "Local", value: "Local", width: "25%",class:'light blue darken-4 white--text' },
        { text: "Acciones", value: "actions", width: "15%",class:'light blue darken-4 white--text' },
      ],
      usuarios: [],
      search: "",
      Nombre: "",
      CodigoPersonal: "",
      CodigoPerfil: "",
      Clave: "",
      CodigoLocal: "",
      ConfirmarClave: "",
      ComprobarClave: "",
      ClaveNueva: "",
      errors: [],
    };
  },
 
  methods: {
    validate() {
      this.$refs.form.validate();
    },
    confirmarClave(value) {
      if (value === this.Clave) {
        this.confirmar = true;
        return true;
      } else {
        this.confirmar = false;
        return "Clave no coincide.";
      }
    },
    comprobarClave() {
      console.log(this.comprobar);
      if (this.comprobar === false) {
        return "Clave no coincide.";
      } else {
        return true;
      }
    },
    abrirDialog() {
      this.dialogEjemplo = true;
      this.mostrarPerfil();
      this.mostrarPersonal();
      this.mostrarLocal();
    },
    limpiar() {
      this.Nombre = "";
      this.CodigoPersonal = "";
      this.CodigoPerfil = "";
      this.Nombre = "";
      this.Clave = "";
      this.CodigoLocal = "";
      this.ConfirmarClave = "";
      this.edit = false;
      this.ClaveNueva = "";
      this.ComprobarClave = "";
      this.$refs.form.resetValidation();
    },
    limpiarError(){
      this.errors = [];
    },
    itemFilaColor: function (item) {
      return item.Vigencia ? "black--text" : "red--text";
    },
    executeEventClick() {
      if (this.edit === false) {
        this.registerUsuario();
      } else {
        this.editUsuario();
      }
    },
    assembleUser() {
      return {
        CodigoPersonal: this.CodigoPersonal,
        CodigoPerfil: this.CodigoPerfil,
        Nombre: this.Nombre,
        Clave: this.Clave,
        CodigoLocal: this.CodigoLocal,
      };
    },

    assembleUserEdit() {
      return {
        Nombre: this.Nombre,
        ComprobarClave: this.ComprobarClave,
        Clave: this.ClaveNueva, // y la antigua? ay
      };
    },

    registerUsuario() {
      if (this.valid == false) return;
      if (this.confirmar == false) return;
      this.saveLoading = true;
      post("usuarios", this.assembleUser()).then(() => {
        this.saveLoading = false;
        this.dialogEjemplo = false;
        this.limpiar();
        Swal.fire({
          title: "Sistema",
          text: "Usuario registrado correctamente.",
          icon: "success",
          confirmButtonText: "OK",
        });
        this.actualizarUsuarios();
      });
    },
    editUsuario() {
      if (this.valid == false) return;
      this.saveLoading = true;
      put("usuarios/" + this.editId, this.assembleUserEdit())
        .then(() => {
          this.limpiar();
          this.comprobar = true;
          this.saveLoading = false;
          this.editId = null;
          this.dialogEjemplo = false;
          Swal.fire({
            title: "Sistema",
            text: "Usuario actualizado correctamente.",
            icon: "success",
            confirmButtonText: "OK",
          });
          this.$refs.usuarioTable.fetchData();
        })
        .catch(() => {
          
            this.errors = ["Contrase;a no valida"];
            this.saveLoading = false;
          
        });
    },
    showEditUsuario(usuario) {
      this.edit = true;
      this.editId = usuario.Codigo;
      this.limpiarError();
      this.mostrarUsuario(usuario.Codigo).then(() => {
        this.dialogEjemplo = true;
        this.mostrarPerfil();
        this.mostrarPersonal();
        this.mostrarLocal();
      });
    },
    deleteUsuario(usuario) {
      patch("usuario/" + usuario.Codigo)
        .then(() => {
          Swal.fire({
            title: "Sistema",
            text: "Usuario actualizado correctamente.",
            icon: "success",
            confirmButtonText: "OK",
          });
          this.actualizarUsuarios();
        })
        .catch(() => {
          this.alert = true;
        });
    },
    actualizarUsuarios() {
      get("usuarios").then((data) => {
        this.usuarios = data;
      });
    },
    mostrarPerfil() {
      get("usuariosperfil").then((data) => {
        this.optionsPerfil = data;
      });
    },
    mostrarPersonal() {
      get("usuariospersonal").then((data) => {
        this.itemsPersonal = data;
      });
    },
    mostrarLocal() {
      get("usuarioslocal").then((data) => {
        this.itemsLocal = data;
      });
    },
    async mostrarUsuario(codigo) {
      const usuario = await get("usuarios/" + codigo);
      this.CodigoPersonal = usuario.CodigoPersonal;
      this.CodigoPerfil = usuario.CodigoPerfil;
      this.Nombre = usuario.Nombre;
      this.Clave = usuario.Clave;
      this.CodigoLocal = usuario.CodigoLocal;
    },
  },
  created() {
    this.actualizarUsuarios();
  },
};
</script>

<style>
</style>