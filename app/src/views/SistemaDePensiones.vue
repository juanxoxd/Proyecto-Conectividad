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
        <h2 class="font-weight-bold text-center">Sistema de Pensiones</h2>
      </v-col>
    </v-row>
    <v-dialog v-model="dialogEjemplo" persistent scrollable max-width="60vw">
      <v-card>
        <v-card-title class="headline indigo darken-4">
          <span v-if="edit" class="headline" style="color: white">
            Editar Sistema De Pensión
          </span>
          <span v-else class="headline" style="color: white">
            Nuevo Sistema de Pensión
          </span>
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="Nombre"
                  :rules="[fieldRules.required]"
                  label="Nombre *"
                  prepend-icon="mdi-domain"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="Siglas"
                  :rules="[fieldRules.required]"
                  label="Siglas *"
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
            @click="(dialogEjemplo = false), limpiar()"
            >Cancelar
          </v-btn>
            <v-btn
            :loading="saveLoading"
            :disabled="saveLoading"
            color="indigo darken-4"
            class="ma-2 white--text"
            depressed
            @mousedown="validate"
            @click="executeEventClick"
            >Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-card>
        <v-row >
            <v-card-title>
                <v-spacer></v-spacer>
            </v-card-title>
            <v-col cols="12" class="pt-0">
                <v-data-table
                    :loading="tableLoading"
                    :headers="headers"
                    :items="sistemasdepensiones"
                    :search="search">
                    <template v-slot:[`item`]="{ item }">
                        <tr v-bind:class="item.Vigencia?'activo':'desactivo'">
                        <td>{{ item.indice }}</td>
                        <td>{{ item.Nombre }}</td>
                        <td>{{ item.Siglas }}</td>
                        <td>
                            <v-tooltip bottom> 
                                <template v-slot:activator="{ on, attrs }">    
                                    <v-icon  class="mr-2" 
                                    v-bind="attrs"
                                    v-on="on"
                                    color="blue-grey"
                                    @click="showEditSistemaDePension(item)">mdi-border-color</v-icon>
                                    </template >
                                <span>Editar</span>
                            </v-tooltip>

                            <v-tooltip bottom> 
                                <template v-slot:activator="{ on, attrs }">
                                <v-icon 
                                v-bind="attrs"
                                v-on="on"
                                 class="mr-2" 
                                 :color="item.Vigencia ? 'red lighten-1' : 'green'"  @click="cambiarEstadoSistemaDePension(item)">
                                {{item.Vigencia? "mdi-close-circle-outline": "mdi-checkbox-marked-circle-outline"}}
                                </v-icon>
                            </template >
                        <span>{{ item.Vigencia ? "Dar de baja" : "Dar de alta" }}</span>
                            </v-tooltip>
                            
                        </td>
                        </tr>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
    </v-card>
    
  </v-container>
</template>

<script>
import { get, post, put, patch} from "../api/api";

export default {
  components: {},
  data() {
    return {
      edit: false,
      alert: false,
      valid: true,
      saveLoading: false,
      dialogEjemplo: false,
      tableLoading: true,
      fieldRules: {
        required: (v) => !!v || "Campo requerido",
      },
      headers: [
        {
          text: "N°",
          align: "start",
          sortable: false,
          value: "indice",
          width: "10%",
          class:'light blue darken-4 white--text'
        },
        { text: "Nombre", align: "start", value: "Nombre", width: "50%", class:'light blue darken-4 white--text' },
        { text: "Siglas", value: "Siglas", width: "30%", class:'light blue darken-4 white--text' },
        { text: "Acciones", value: "actions", width: "20%", class:'light blue darken-4 white--text' },
      ],
      sistemasdepensiones: [],
      search: "",
      Nombre: "",
      Siglas: "",
    };
  },
  methods: {
    fetchData() {
      this.tableLoading = true;
      get("sistemasdepensiones").then((data) => {
        this.tableLoading = false;
        this.sistemasdepensiones = data;
      });
    },
    validate() {
      this.$refs.form.validate();
    },
    limpiar() {
      this.Nombre = "";
      this.Siglas = "";
    },
    executeEventClick() {
      if (this.edit === false) {
        this.registerSistemaDePension();
      } else {
        this.editSistemaDePension();
      }
    },
    assembleSistemaDePension() {
       return {
        Nombre: this.Nombre,
        Siglas: this.Siglas          
        }
    },

    registerSistemaDePension() {
      if (this.valid == false) return;
      this.saveLoading = true;
      post("sistemasdepensiones", this.assembleSistemaDePension()).then(() => {
        this.saveLoading = false;
        this.dialogEjemplo = false;
        this.fetchData();
        this.limpiar();
        this.actualizarSistemasDePensiones();
      });
    },
    editSistemaDePension() {
      if (this.valid == false) return;
      this.saveLoading = true;
      put("sistemasdepensiones/" + this.editId,this.assembleSistemaDePension()).then(() => {
          this.saveLoading = false;
          this.editId = null;
          this.dialogEjemplo = false;
          this.edit = false;
          this.fetchData();
          this.limpiar();
        }).catch(() => {
          this.alert = true;          ///dfs
        });
    },
    showEditSistemaDePension(sistemadepension) {
      this.edit = true;
      this.editId = sistemadepension.Codigo;
      this.mostrarSistemaDePension(sistemadepension.Codigo).then(() => {
        this.dialogEjemplo = true;
      });
    },
    cambiarEstadoSistemaDePension(sistemadepension) {
      patch("sistemadepension/" + sistemadepension.Codigo)
        .then(() => {
          this.fetchData();
          this.actualizarSistemasDePensiones();
        })
        .catch(() => {
          this.alert = true;
        });
    },
    actualizarSistemasDePensiones() {
       get("sistemasdepensiones").then((data) => {
        this.sistemasdepensiones = data;
      });
    },
    async mostrarSistemaDePension(codigo) {
      const sistemadepension = await get("sistemasdepensiones/" + codigo);
      this.Nombre = sistemadepension.Nombre;
      this.Siglas = sistemadepension.Siglas;
    },
  },
  created() {
    this.actualizarSistemasDePensiones();
    this.fetchData();
  },
};
</script>

<style>
  tr.desactivo{
    color: red;
  }
</style>