<?php
class sideBarOptions {

    public function usuarios()
    {
        $html = <<<Html
        <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="fas fa-users" style="color: #F21331;"></i>
            <p>
                Usuarios
                <i class="right fas fa-angle-left"></i>
            </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="/administrador/usuarios/registrar-usuarios" name="sidebarEnlace" class="nav-link" data-page="usuarios/registrar-usuarios">
                <i class="fas fa-book"></i>
                <p>Registrar</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/administrador/usuarios/actualizar-usuarios" name="sidebarEnlace" class="nav-link" data-page="usuarios/actualizar-usuarios">
                <i class="fas fa-edit"></i>
                <p>Administrar usuarios</p>
                </a>
            </li>
            </ul>
        </li>
        Html;
        return $html;
    }
    
    public function registro()
    {
        $html = <<<Html
        <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="fas fa-hard-hat" style="color: #edf019;"></i>
            <p>
                Registro
                <i class="right fas fa-angle-left"></i>
            </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="/administrador/director/registrar-archivo" name="sidebarEnlace" class="nav-link" data-page="director/registrar-archivo">
                    <i class="fas fa-book"></i>
                    <p>Registrar</p>
                    </a>
                </li>
            </ul>
        </li>
        Html;
        return $html;
    }
}
?>