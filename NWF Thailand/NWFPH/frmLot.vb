Imports Newtonsoft.Json.Linq
Public Class frmLot
    Dim api As New ApiClass()
    Private Sub frmLot_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Dim postData As New Dictionary(Of String, String) From {
              {"itemkey", frmPreweigh.txtItemKey.Text},
              {"lotno", frmPreweigh.txtlotno.Text}
            }
        Dim myrequest = api.post_request("PartialPick/getlotno", postData)
        'Debug.Write(myrequest)
        Dim jsonObject As JObject = DirectCast(myrequest, JObject)

        If jsonObject.ContainsKey("data") Then
            Dim dta As JArray = jsonObject("data")

            For Each item As JToken In dta
                dglot.Rows.Add({
                      item("ItemKey"),
                      item("LotNo"),
                      item("BinNo"),
                      item("qty"),
                      item("DateExpiry")
                  })

            Next
        End If
    End Sub

    Private Sub dglot_CellContentDoubleClick(sender As Object, e As DataGridViewCellEventArgs) Handles dglot.CellContentDoubleClick


        If e.RowIndex >= 0 Then
            Me.Close()
            Dim row As DataGridViewRow = dglot.Rows(e.RowIndex)
            'frmPreweigh.sellotno = row.Cells("LotNo").Value
            frmPreweigh.selitemkey = row.Cells("itemkey").Value
            frmPreweigh.sellotno = row.Cells("LotNo").Value
            frmPreweigh.selbinno = row.Cells("BinNo").Value
            'frmPreweigh.ProcessScan(itemkey, lotno)
        End If




    End Sub
End Class