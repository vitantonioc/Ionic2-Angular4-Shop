import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';

import { ProductosService } from "../../providers/productos";
import { ProductoPage } from "../index.paginas";


@IonicPage()
@Component({
  selector: 'page-busqueda',
  templateUrl: 'busqueda.html',
})
export class Busqueda {

  productoPage = ProductoPage;

  constructor(public navCtrl: NavController,
              public navParams: NavParams,
              private _ps:ProductosService) {
  }


  buscar_productos(ev: any){

    let valor = ev.target.value;
    console.log(valor);

    this._ps.buscar_producto( valor );
  }



}
