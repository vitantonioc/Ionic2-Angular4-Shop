import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { Busqueda } from './busqueda';

@NgModule({
  declarations: [
    Busqueda,
  ],
  imports: [
    IonicPageModule.forChild(Busqueda),
  ],
  exports: [
    Busqueda
  ]
})
export class BusquedaModule {}
