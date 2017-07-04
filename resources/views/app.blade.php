@extends('layout')

@section('content')
  <div id="app" class="wrapper">
    <div class="sidebar">
      <div class="sidebar-wrapper">
        <div class="logo">
          <a href="{{ route('app') }}" class="simple-text">
            CodeDev
          </a>
        </div>

        <ul class="nav">
          <router-link tag="li" :to="{ name: 'dashboard' }">
            <router-link :to="{ name: 'dashboard' }">
              <i class="fa fa-dashboard"></i>
              <p>Dashboard</p>
            </router-link>
          </router-link>
          <router-link tag="li" :to="{ name: 'client.index' }">
            <router-link :to="{ name: 'client.index' }">
              <i class="fa fa-users"></i>
              <p>Clientes</p>
            </router-link>
          </router-link>
          <router-link tag="li" :to="{ name: 'project.index' }">
            <router-link :to="{ name: 'project.index' }">
              <i class="fa fa-wrench"></i>
              <p>Projetos</p>
            </router-link>
          </router-link>
        </ul>
      </div>
      <div class="sidebar-background"></div>
    </div>

    <div class="main-panel">
      <nav class="navbar navbar-default navbar-fixed">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  {{ auth()->user()->name }}
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something</a></li>
                  <li class="divider"></li>
                  <li>
                      <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        Sair
                      </a>

                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                      </form>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <div class="content">

        <router-view></router-view>

      </div>

      <footer class="footer">
        <div class="container-fluid">
          <p class="copyright pull-right">
            &copy; 2017 <a href="http://www.codedev.com.br">CodeDev</a>. Todos os direitos reservados.
          </p>
        </div>
      </footer>
    </div>
  </div>
@endsection
