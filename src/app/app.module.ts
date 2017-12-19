import { BrowserModule } from '@angular/platform-browser';
import { ErrorHandler, NgModule } from '@angular/core';
import { IonicApp, IonicErrorHandler, IonicModule } from 'ionic-angular';
import { SplashScreen } from '@ionic-native/splash-screen';
import { StatusBar } from '@ionic-native/status-bar';

import { HttpModule } from '@angular/http';

import { MyApp } from './app.component';
import { HomePage } from '../pages/home/home';

// Pipes
import { ImagenPipe } from "../pipes/imagen";

// storage
import { IonicStorageModule } from '@ionic/storage';

// servicios
import { CarritoService, UsuarioService, ProductosService } from "../providers/index.services";

// Paginas
import {  CarritoPage,
          CategoriasPage,
          LoginPage,
          OrdenesPage,
          OrdenesDetallePage,
          PorCategoriasPage,
          TabsPage,
          ProductoPage } from "../pages/index.paginas";


@NgModule({
  declarations: [
    MyApp,
    ImagenPipe,
    HomePage,
    CarritoPage,
    CategoriasPage,
    LoginPage,
    OrdenesPage,
    OrdenesDetallePage,
    PorCategoriasPage,
    TabsPage,
    ProductoPage
  ],
  imports: [
    BrowserModule,
    HttpModule,
    IonicModule.forRoot(MyApp),
    IonicStorageModule.forRoot()
  ],
  bootstrap: [IonicApp],
  entryComponents: [
    MyApp,
    HomePage,
    CarritoPage,
    CategoriasPage,
    LoginPage,
    OrdenesPage,
    OrdenesDetallePage,
    PorCategoriasPage,
    TabsPage,
    ProductoPage
  ],
  providers: [
    StatusBar,
    SplashScreen,
    CarritoService,
    UsuarioService,
    ProductosService,
    {provide: ErrorHandler, useClass: IonicErrorHandler}
  ]
})
export class AppModule {}
