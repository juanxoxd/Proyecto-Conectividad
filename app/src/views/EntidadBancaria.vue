<template>
  <v-main>
    <v-row align="center" class="pa-5 align-center">
      <v-col cols="4">
        <v-alert
          v-model="alert"
          text
          prominent
          type="error"
          icon="mdi-cloud-alert"
          close-text="Close Alert"
          dismissible
          >Ocurrio un error.</v-alert
        >
      </v-col>
    </v-row>
    <v-row class="pa-5 align-center">
      <v-col>
        <v-btn
          fab
          large
          dark
          color="blue darken-3"
          @click="dialogEjemplo = true"
        >
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-col>
      <v-col cols="11">
        <h2 class="font-weight-regular text-center">
          Mantenimiento de Entidad
        </h2>
      </v-col>
    </v-row>
    <v-dialog v-model="dialogEjemplo" persistent scrollable max-width="60vw">
      <v-card>
        <v-card-title class="headline indigo darken-4">
          <span v-if="edit" class="headline" style="color: white"
            >Editar Entidad</span
          >
          <span v-else-if="ver" class="headline" style="color: white"
            >Ver Entidad</span
          >
          <span v-else class="headline" style="color: white"
            >Nueva Entidad Financiera</span
          >
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  :disabled="ver"
                  v-model="RazonSocial"
                  :rules="[fieldRules.required]"
                  label="Razón Social"
                  prepend-icon="mdi-domain"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  :disabled="ver"
                  v-model="Siglas"
                  :rules="[fieldRules.required]"
                  label="Siglas Entidad"
                  maxlength="11"
                  prepend-icon="mdi-domain"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            v-if="!ver"
            color="indigo darken-4"
            text
            @click="(dialogEjemplo = false), limpiar()"
            >Cerrar</v-btn
          >
          <v-btn
            v-if="!ver"
            :loading="saveLoading"
            :disabled="saveLoading"
            color="indigo darken-4"
            class="ma-2 white--text"
            depressed
            @mousedown="validate"
            @click="executeEventClick"
            >Guardar</v-btn
          >
          <v-btn
            v-if="ver"
            color="indigo darken-4"
            text
            @click="(dialogEjemplo = false), limpiar()"
            >Cerrar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-tabs>
      <v-tab>Lista</v-tab>

      <v-tab-item>
        <tables-mostrar
          :headers="headers"
          :options="options"
          :withOptions="true"
          ref="empresaTable"
          entity="tablaEntidadBancaria"
        />
      </v-tab-item>
    </v-tabs>
  </v-main>
</template>

<script>
import { del, get, enviarConArchivos, patch } from "../api/api";

export default {
  components: {
    TablesMostrar: () => import("../components/TablesMostrar"),
  },
  data() {
    return {
      edit: false,
      ver: false,
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
        {
          text: "#",
          align: "start",
          sortable: false,
          value: "indice",
          width: "10%",
        },
        { text: "Siglas" ,value: "Siglas", width: "20%"},
        { text: "Razon Social", value: "RazonSocial", width: "30%" },        
        { text: "Acciones", value: "actions", width: "20%" },
      ],
      options: [
        {
          name: "Editar",
          icon: "mdi-pencil",
          function: this.showEditEmpresa,
        },
        {
          name: "Eliminar",
          icon: "mdi-delete",
          function: this.cambiarEstadoEmpresa,
        },
      ],
      entidades: [],
      search: "",
      RazonSocial: "",
      Siglas: "",      

      /*Fin de Cosas */
    };
  },
  methods: {
    validate() {
      this.$refs.form.validate();
    },
    limpiar() {
      /*Cosas*/
      this.Siglas = "";
      /*Fin de Cosas */
      this.RazonSocial = "";
      this.ver = false;
      this.edit = false;
    },
    executeEventClick() {
      if (this.edit === false) {
        this.registerEmpresa();
      } else {
        this.editEmpresa();
      }
    },
    assembleEmpresa() {
      let form = new FormData();
      form.append("RazonSocial", this.RazonSocial);
      form.append("Siglas", this.Siglas);
      return form;
    },

    registerEmpresa() {
      if (this.valid == false) return;
      this.saveLoading = true;
      enviarConArchivos("entidadesBancarias", this.assembleEmpresa()).then(
        () => {
          this.saveLoading = false;
          this.dialogEjemplo = false;
          this.$refs.empresaTable.fetchData();
          this.limpiar();
          this.actualizarEmpresas();
        }
      );
    },
    editEmpresa() {
      if (this.valid == false) return;
      this.saveLoading = true;
      enviarConArchivos(
        "entidadesBancarias/" + this.editId,
        this.assembleEmpresa()
      )
        .then(() => {
          this.saveLoading = false;
          this.editId = null;
          this.dialogEjemplo = false;
          this.$refs.empresaTable.fetchData();
          this.limpiar();
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
    verEmpresa(empresa) {
      this.ver = true;
      this.mostrarEmpresa(empresa.Codigo).then(() => {
        this.dialogEjemplo = true;
      });
    },
    deleteEmpresa(empresa) {
      del("empresa/" + empresa.Codigo)
        .then(() => {
          this.$refs.empresaTable.fetchData();
          this.actualizarEmpresas();
        })
        .catch(() => {
          this.alert = true;
        });
    },
    cambiarEstadoEmpresa(empresa) {
      patch("entidadBancaria/" + empresa.Codigo)
        .then(() => {
          this.$refs.empresaTable.fetchData();
          this.actualizarEmpresas();
        })
        .catch(() => {
          this.alert = true;
        });
    },
    actualizarEmpresas() {
      get("tablaEntidadBancaria").then((data) => {
        this.entidades = data;
      });
    },
    async mostrarEmpresa(codigo) {
      const empresa = await get("entidadesBancarias/" + codigo);
      this.Siglas = empresa.Siglas;
      this.RazonSocial = empresa.RazonSocial;
    },
    mostrarEntidad() {
      get("tablaEntidadBancaria").then((data) => {
        this.entidades = data;
      });
    },
  },
  mounted() {
    this.mostrarEntidad();
  },
};
</script>

<style>
  tr.desactivo{
    background-color: rgb(165, 93, 88);
    color: silver;
  }
</style>