if ("serial" in navigator) {
    // The Web Serial API is supported.
}
var port;

var xdata = [];
/*
document.querySelector('#btnid').addEventListener('click', async () => {
    // Prompt user to select any serial port.
    const filters = [
        { usbVendorId: 0x1A86, usbProductId: 0x7523 },
    ];

    port = await navigator.serial.requestPort({filters});
    await port.open({ baudRate: 9600 });

    const reader = port.readable.getReader({ mode: "byob" });
    let buffer = new ArrayBuffer(1024);
    buffer = await readInto(reader, buffer);
    console.log(buffer);
    buffer = await readInto(reader, buffer);
    console.log(buffer);
});

async function readInto(reader, buffer) {
    let offset = 0;
    while (offset < buffer.byteLength) {
      const { value, done } = await reader.read(
        new Uint8Array(buffer, offset)
      );
      if (done) {
        break;
      }
      buffer = value.buffer;
      offset += value.byteLength;
    }
    return buffer;
}
*/
var portdata = 0;
var maxdata = 0;
var mindata = 0;
var adata = 0;

document.querySelector('#btnid').addEventListener('click', async () => {
    // Prompt user to select any serial port.
    const filters = [
      //  { usbVendorId: 0x1A86, usbProductId: 0x7523 },
    ];

    port = await navigator.serial.requestPort({filters});
    await port.open({ baudRate: 9600 });
    
    const textDecoder = new TextDecoderStream();
    const readableStreamClosed = port.readable.pipeTo(textDecoder.writable);
    const reader = textDecoder.readable.getReader();
    
    // Listen to data coming from the serial device.
    
    while (true) {
      const { value, done } = await reader.read();
      if (done) {
        // Allow the serial port to be closed later.
        reader.releaseLock();
        break;
      }
        //xdata.push(value);
        //console.log(value);
        
        if (value && (value.indexOf('.') >= 0) ) {
          portdata = value.replace( /[^\d\.]*/g, '');

          if($("#tofill").val() != portdata ){

            if(portdata != ''){
              adata = $("#tofill").val();
              maxdata = $("#maxw").val();
              mindata = $("#minw").val();
              
              $("#mydata").val(portdata);
              
              switch(true){
                  case (portdata > maxdata):{
                      $("#wbar").addClass('bg-danger');
                      $("#wbar").removeClass('bg-success');
                      $("#wbar").removeClass('bg-info');
                      break;
                  }

                  case (portdata > mindata && portdata < maxdata):{
                      $("#wbar").addClass('bg-success');
                      $("#wbar").removeClass('bg-danger');
                      $("#wbar").removeClass('bg-info');
                      break;
                  }

                  default:{
                      $("#wbar").removeClass('bg-danger');
                      $("#wbar").removeClass('bg-success');
                      $("#wbar").addClass('bg-info');
                      break;
                  }
              }

              $("#wbar").css({width: ( (portdata/adata)*100)+'%'  });
              $("#lbar").text(portdata);
          }

          }
            
        }
        await setTimeout(5000);
    }
  
    
});


/*

  
// Filter on devices with the Arduino Uno USB Vendor/Product IDs.
const filters = [
    { usbVendorId: 0x2341, usbProductId: 0x0043 },
    { usbVendorId: 0x2341, usbProductId: 0x0001 }
];
  
// Prompt user to select an Arduino Uno device.
const port = await navigator.serial.requestPort({ filters });
  
const { usbProductId, usbVendorId } = port.getInfo();
  
// Prompt user to select any serial port.
const port = await navigator.serial.requestPort();
  
// Wait for the serial port to open.
await port.open({ baudRate: 9600 });
  
  
  while (port.readable) {
    const reader = port.readable.getReader();
  
    try {
      while (true) {
        const { value, done } = await reader.read();
        if (done) {
          // Allow the serial port to be closed later.
          reader.releaseLock();
          break;
        }
        if (value) {
          console.log(value);
        }
      }
    } catch (error) {
      // TODO: Handle non-fatal read error.
    }
  }
  
  
  const textDecoder = new TextDecoderStream();
  const readableStreamClosed = port.readable.pipeTo(textDecoder.writable);
  const reader = textDecoder.readable.getReader();
  
  // Listen to data coming from the serial device.
  while (true) {
    const { value, done } = await reader.read();
    if (done) {
      // Allow the serial port to be closed later.
      reader.releaseLock();
      break;
    }
    // value is a string.
    console.log(value);
  }
  */