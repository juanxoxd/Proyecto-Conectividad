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
                  :rules="[fieldRules.required, fieldRules.validateRazonSocial]"
                  label="Razón Social"
                  prepend-icon="mdi-domain"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  :disabled="ver"
                  v-model="Siglas"
                  :rules="[fieldRules.required, fieldRules.validateSiglas]"
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
    <!-- <v-tabs>
      <v-tab>Lista</v-tab>

      <v-tab-item>
        <mostrar-entidades
          :headers="headers"
          :options="options"
          :withOptions="true"
          ref="empresaTable"
          entity="tablaentidadbancaria"
        />
      </v-tab-item>
    </v-tabs> -->
    <v-row class="justify-center">
      <v-col cols="11">
        <v-card>
          <v-card-title>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Buscar"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title>
          <v-card-text>
            <v-data-table
              :loading="tableLoading"
              :headers="headers"
              :items="entidades"
              :search="search"
              >
              <template v-slot:[`item`]="{ item }">
                <tr v-bind:class="item.Vigencia?'activo':'desactivo'">
                  <td>{{ item.indice }}</td>
                  <td>{{ item.RazonSocial }}</td>
                  <td>{{ item.Siglas }}</td>
                  <td>
                    <v-icon class="mr-2" @click="showEditEntidad(item)">mdi-pencil</v-icon>
                    <v-icon class="mr-2" @click="entidadVigenciaEntidad(item)" :color="item.Vigencia?'error':'success'">
                      {{item.Vigencia? "mdi-close-circle-outline": "mdi-checkbox-marked-circle-outline"}}
                    </v-icon>
                  </td>
                </tr>
              </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-main>
</template>

<script>
import { del, get, put, post, patch } from "../api/api";
import Swal from "sweetalert2";

export default {
  components: {},
  data() {
    return {
      tableLoading: true,
      edit: false,
      ver: false,
      alert: false,
      valid: true,
      saveLoading: false,
      dialogEjemplo: false,
      fieldRules: {
        required: (v) => !!v || "Campo requerido",
        validateRazonSocial: (v) => (v.length > 7) || "La Razón Social debe contener al menos 7 caracteres",
        validateSiglas: (v) => (v.length > 2) || "La Razón Social debe contener al menos 2 caracteres",
        validateRuc: (v) => v.length == 11 || "RUC incorrecto.",
      },
      headers: [
        {
          text: "N°",
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
          function: this.showEditEntidad,
        },
        {
          name: "Eliminar",
          icon: "mdi-close-circle-outline",
          function: this.entidadVigenciaEntidad,
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
    fetchData() {
      this.tableLoading = true;
      get("tablaentidadbancaria").then((data) => {
        this.tableLoading = false;
        this.entidades = data;
      });
    },
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
        this.registerEntidad();
      } else {
        this.editEntidad();
      }
    },
    assembleEntidad() {
      // let form = new FormData();
      // form.append("RazonSocial", this.RazonSocial);
      // form.append("Siglas", this.Siglas);
      // return form;
      console.log(this.Codigo);
      return {
        Codigo: this.Codigo,
        RazonSocial: this.RazonSocial,
        Siglas: this.Siglas,
      };
    },

    registerEntidad() {
      if (this.valid == false) return;
      this.saveLoading = true;
      post("entidadesbancarias", this.assembleEntidad()).then(
        () => {
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "Entidad registrada !",
            icon: "success",
            confirmButtonText: "Ok",
            timer: 2500,
          });
          this.saveLoading = false;
          this.dialogEjemplo = false;
          this.fetchData();
          this.limpiar();
          this.actualizarEmpresas();
        }
      );
    },
    // putNew(){
    //   let form = new FormData();
    //   form.append("RazonSocial", this.RazonSocial);
    //   form.append("Siglas", this.Siglas);
    //   form.append("id", this.editId);
    //   return form;
    // },
    editEntidad() {
      if (this.valid == false) return;
      this.saveLoading = true;
      put(
        "entidadesbancarias/" + this.editId,
        this.assembleEntidad()
      )
        .then(() => {
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "Entidad actualizada !",
            icon: "success",
            confirmButtonText: "Ok",
            timer: 2500,
          });
          this.saveLoading = false;
          this.editId = null;
          this.dialogEjemplo = false;
          this.fetchData();
          this.limpiar();
        })
        .catch(() => {
          this.alert = true;
        });
    },
    showEditEntidad(entidad) {
      this.edit = true;
      this.editId = entidad.Codigo;
      this.mostrarEmpresa(entidad.Codigo).then(() => {
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
    entidadVigenciaEntidad(empresa) {
      patch("entidadBancaria/" + empresa.Codigo)
        .then(() => {
          this.fetchData();
          this.actualizarEmpresas();
        })
        .catch(() => {
          this.alert = true;
        });
    },
    actualizarEmpresas() {
      get("tablaentidadbancaria").then((data) => {
        this.entidades = data;
      });
    },
    async mostrarEmpresa(codigo) {
      const empresa = await get("entidadesbancarias/" + codigo);
      this.Siglas = empresa.Siglas;
      this.RazonSocial = empresa.RazonSocial;
    },
    mostrarEntidad() {
      get("tablaentidadbancaria").then((data) => {
        this.entidades = data;
      });
    },
  },
  mounted() {
    this.mostrarEntidad();
  },
  created() {    
    this.fetchData();
  },
};
</script>
<style>
  tr.desactivo{
    color: red;
  }
  th{
    background: #01579B;
    color: white;
  }
</style>