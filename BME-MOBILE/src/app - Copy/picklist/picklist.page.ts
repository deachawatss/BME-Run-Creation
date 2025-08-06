import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-picklist',
  templateUrl: './picklist.page.html',
  styleUrls: ['./picklist.page.scss'],
})
export class PicklistPage implements OnInit {
  test = '';
  search = '';
  rec = 0;
  constructor() { }

  ngOnInit() {
  }

  btnBack(){
   // this.navCtrl.navigateRoot('/home');
  }

  xhex(){
    //this.test = 'success';
  }

  nClass(e:any){
    /*
    if(e == this.rec)
      return this.test;
    else
    */
      return '';
  }
}
