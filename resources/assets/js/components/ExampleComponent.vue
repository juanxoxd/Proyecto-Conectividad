<template>
    <main class="main">
        <div class="container-fluid mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col-md-6">
                            <div
                                class="d-flex justify-content-between align-items-center"
                            >
                                <h4>Gestión de Empresas</h4>
                                <button
                                    class="btn btn-success nuevo"
                                    data-toggle="modal"
                                    data-target="#modalEmpresa"
                                    @click="limpiarModal()"
                                >
                                    Nueva Empresa
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select
                                    class="form-control col-md-3"
                                    v-model="criterio"
                                >
                                    <option value="Seleccione"
                                        >Seleccione</option
                                    >
                                    <option value="razonSocial">Nombre</option>
                                    <option value="ruc">RUC</option>
                                </select>
                                <input
                                    type="text"
                                    @keyup.enter="listarEmpresa(1, buscar)"
                                    v-model="buscar"
                                    class="form-control document-nice"
                                    placeholder="Buscar aqui"
                                />
                                <button
                                    type="submit"
                                    @click.prevent="
                                        listarEgreso(1, buscar, mes, anho)
                                    "
                                    class="btn btn-primary btn-border"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-12 table-responsive">
                            <table
                                class="table table-bordered table-striped table-sm text-center"
                            >
                                <thead>
                                    <tr>
                                        <th class="text-center">Opciones</th>
                                        <th class="text-center">
                                            Razon Social
                                        </th>
                                        <th class="text-center">RUC</th>
                                        <th class="text-center">Correo</th>
                                        <th class="text-center">Vigencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="empresa in arregloEmpresa"
                                        :key="empresa.idEmpresa"
                                    >
                                        <td>
                                            <!-- //opciones -->
                                            <button
                                                type="button"
                                                @click="
                                                    cargarDatosEmpresa(empresa)
                                                "
                                                class="btn btns btn-warning"
                                                data-toggle="modal"
                                                data-target="#modalEmpresa"
                                            >
                                                <i
                                                    class="feather  icon-edit"
                                                ></i>
                                            </button>
                                            <template>
                                                <button
                                                    v-if="!empresa.vigencia"
                                                    type="button"
                                                    @click="
                                                        activarEmpresa(
                                                            empresa.idEmpresa
                                                        )
                                                    "
                                                    class="btn btns btn-success"
                                                >
                                                    <i
                                                        class="feather  icon-check-circle"
                                                    ></i>
                                                </button>
                                                <button
                                                    v-else
                                                    type="button"
                                                    @click="
                                                        desactivarEmpresa(
                                                            empresa.idEmpresa
                                                        )
                                                    "
                                                    class="btn btns btn-danger"
                                                >
                                                    <i
                                                        class="feather icon-x-circle"
                                                    ></i>
                                                </button>
                                            </template>
                                        </td>
                                        <td v-text="empresa.razonSocial"></td>
                                        <td v-text="empresa.ruc"></td>
                                        <td v-text="empresa.correo"></td>
                                        <td>
                                            <span
                                                class="badge badge-pill badge-success"
                                                v-if="empresa.vigencia"
                                                >Habilitada</span
                                            >
                                            <span
                                                class="badge badge-pill badge-danger"
                                                v-else
                                                >Deshabilitada</span
                                            >
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <nav>
                        <ul class="pagination mb-0">
                            <li
                                class="page-item"
                                v-if="pagination.current_page > 1"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="
                                        cambiarPagina(
                                            pagination.current_page - 1
                                        )
                                    "
                                    >Ant</a
                                >
                            </li>
                            <li
                                class="page-item"
                                v-for="page in pagesNumber"
                                :key="page"
                                :class="[page == isActived ? 'active' : '']"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="cambiarPagina(page)"
                                    v-text="page"
                                ></a>
                            </li>
                            <li
                                class="page-item"
                                v-if="
                                    pagination.current_page <
                                        pagination.last_page
                                "
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="
                                        cambiarPagina(
                                            pagination.current_page + 1
                                        )
                                    "
                                    >Sig</a
                                >
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div
                class="modal fade"
                tabindex="-1"
                id="modalEmpresa"
                role="dialog"
                aria-labelledby="myModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-primary modal-lg">
                    <div class="modal-content mods">
                        <div class="modal-header modle">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-1">
                                <div class="col-md-8">
                                    <p class="mb-0">Razón Social:</p>
                                    <input
                                        v-model="razonSocial"
                                        type="text"
                                        class="form-control"
                                    />
                                    <small
                                        v-if="errors.razonSocial"
                                        class="text-danger"
                                        v-text="errors.razonSocial[0]"
                                    ></small>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-0">Ruc:</p>
                                    <input
                                        v-model="ruc"
                                        type="text"
                                        class="form-control"
                                    />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 mb-1">
                                    <div class="mb-1">
                                        <p class="mb-0">Correo:</p>
                                        <input
                                            v-model="correo"
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                    <div class="mb-1">
                                        <p class="mb-0">Facebook:</p>
                                        <input
                                            v-model="facebook"
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                    <div class="mb-1">
                                        <p class="mb-0">Instagram:</p>
                                        <input
                                            v-model="instagram"
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                    <div class="mb-1">
                                        <p class="mb-0">WhatsApp:</p>
                                        <input
                                            v-model="whatsapp"
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex flex-wrap">
                                    <label>
                                        Imágen
                                    </label>

                                    <input
                                        type="file"
                                        id="imagen"
                                        name="avatar"
                                        accept="image/png, image/jpeg"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="submit"
                                class="btn btns btn-secondary"
                                data-dismiss="modal"
                            >
                                Cerrar
                            </button>
                            <button
                                type="submit"
                                v-if="tipoAccion == 1"
                                class="btn btns btn-primary"
                                @click="registrarEmpresa()"
                            >
                                Registrar
                            </button>
                            <button
                                type="submit"
                                v-if="tipoAccion == 2"
                                class="btn btns btn-primary"
                                @click="actualizarEmpresa()"
                            >
                                Actualizar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
export default {
    components: {},
    data() {
        return {
            tipoAccion: 1,
            tituloModal: "Registro de Empresa",
            errors: [],
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0
            },
            offset: 3,
            /* Nuevas Variables */
            arregloEmpresa: [],
            razonSocial: "",
            ruc: "",
            facebook: "",
            instagram: "",
            whatsapp: "",
            correo: "",
            idEmpresaSeleccionada: "",
            criterio: "Seleccione",
            buscar: ""
            //este arreglo va a ser llenado por obtener()

            /* Fin de las Nuevas Variables */
        };
    },
    computed: {
        isActived: function() {
            return this.pagination.current_page;
        },
        pagesNumber: function() {
            if (!this.pagination.to) {
                return [];
            }
            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            var to = from + this.offset * 2;
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            var pagesArrays = [];
            while (from <= to) {
                pagesArrays.push(from);
                from++;
            }
            return pagesArrays;
        }
    },
    methods: {
        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
            me.listarEmpresa(page, this.buscar);
        },
        numCero: function(lengthCaracter, total) {
            var resta = total - lengthCaracter;
            var count = 0;
            var copy = "0";
            var result = "";
            if (resta != 0) {
                while (count != resta) {
                    result = result + copy;
                    count++;
                }
            }
            return result;
        },
        cargarDatosEmpresa(DataEmpresa) {
            this.tipoAccion = 2;
            this.tituloModal = "Actualizar Empresa";
            this.idEmpresaSeleccionada = DataEmpresa["idEmpresa"];
            /* console.log(this.idEmpresaSeleccionada); */
            this.razonSocial = DataEmpresa["razonSocial"];
            this.ruc = DataEmpresa["ruc"];
            this.correo = DataEmpresa["correo"];
            this.facebook = DataEmpresa["urlFacebook"];
            this.instagram = DataEmpresa["urlInstagram"];
            this.whatsapp = DataEmpresa["urlWhatsApp"];
        },
        actualizarEmpresa() {
            let me = this;
            axios
                .put("/Empresa/update", {
                    idEmpresa: me.idEmpresaSeleccionada,
                    razonSocial: me.razonSocial,
                    ruc: me.ruc,
                    urlFacebook: me.facebook,
                    urlInstagram: me.instagram,
                    urlWhatsApp: me.whatsapp,
                    correo: me.correo
                })
                .then(response => {
                    toastr.success("Empresa Actualizada.");
                    me.cerrarModal();
                    me.listarEmpresa(1, this.buscar);
                })
                .catch(error => {
                    /* this.errors = error.response.data.errors; */
                    console.log(error);
                });
        },
        registrarEmpresa() {
            this.tipoAccion = 1;
            let me = this;
            axios
                .post("/Empresa/store", {
                    razonSocial: me.razonSocial,
                    ruc: me.ruc,
                    urlFacebook: me.facebook,
                    urlInstagram: me.instagram,
                    urlWhatsApp: me.whatsapp,
                    correo: me.correo
                })
                .then(response => {
                    toastr.success("Empresa Registrada.");
                    me.cerrarModal();
                    me.listarEmpresa(1, this.buscar);
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    /*   console.log(error); */
                });
        },
        cerrarModal() {
            $("#modalEmpresa").modal("hide");
            $("body").removeClass("modal-open");
            $(".modal-backdrop").remove();
        },
        desactivarEmpresa(idEmpresa) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn ml-1 btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons
                .fire({
                    title: "¿Está seguro de desactivar esta Empresa?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Aceptar",
                    cancelButtonText: "Cancelar",
                    reverseButtons: true
                })
                .then(result => {
                    if (result.value) {
                        let me = this;
                        axios
                            .put("/Empresa/desactivate", {
                                idEmpresa: idEmpresa
                            })
                            .then(function(response) {
                                me.listarEmpresa(1, me.buscar);
                            })
                            .catch(function(error) {
                                console.log(error);
                            });
                        swalWithBootstrapButtons.fire(
                            "Descativada",
                            "La Empresa ha sido desactivada exitosamente.",
                            "success"
                        );
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                    }
                });
        },
        activarEmpresa(idEmpresa) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn ml-1 btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons
                .fire({
                    title: "¿Está seguro de activar esta Empresa?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Aceptar",
                    cancelButtonText: "Cancelar",
                    reverseButtons: true
                })
                .then(result => {
                    if (result.value) {
                        let me = this;
                        axios
                            .put("/Empresa/activate", {
                                idEmpresa: idEmpresa
                            })
                            .then(function(response) {
                                me.listarEmpresa(1, me.buscar);
                            })
                            .catch(function(error) {
                                console.log(error);
                            });
                        swalWithBootstrapButtons.fire(
                            "Activada",
                            "La Empresa ha sido activada exitosamente.",
                            "success"
                        );
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                    }
                });
        },
        asignarCriterio(texto) {
            console.log("activa");
            this.criterio = texto;
        },
        listarEmpresa(page, buscar) {
            var url =
                "/Empresa?page=" +
                page +
                "&textoBuscar=" +
                buscar +
                "&criterio=" +
                this.criterio;
            axios
                .get(url)
                .then(response => {
                    var respuesta = response.data;
                    this.arregloEmpresa = respuesta.empresa.data;
                    this.pagination = respuesta.pagination;
                    console.log(this.arregloEmpresa);
                })
                .catch(error => {
                    console.log(error);
                });
        },
        limpiarModal() {
            this.tipoAccion = 1;
            this.errors = [];
            this.razonSocial = "";
            this.ruc = "";
            this.facebook = "";
            this.instagram = "";
            this.whatsapp = "";
            this.correo = "";
        }
    },
    mounted() {
        this.listarEmpresa(1, "");
    }
};
</script>
