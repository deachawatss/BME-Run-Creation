Imports Newtonsoft.Json.Linq

Public Class frmFetchWtRunModal
    Private Sub frmFetchWtRunModal_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Dim mdb As New MySQL()
        Dim query = "
            Select 
                    tbl_runhrd.runno,
                    tbl_runhrd.formulaid,
                    tbl_runhrd.batchsize,
                    count(*) as tbatch,
                    group_concat(tbl_rundata.batchno) as batchlist
                    from tbl_runhrd
                    inner join tbl_rundata on tbl_rundata.runid = tbl_runhrd.runid
                    group by tbl_runhrd.runno
                    
        "
        Dim userData = mdb.SelectData(query)
        DataGridView1.Rows.Clear()
        Dim dataTable As New DataTable()


        'While (userData.Read())

        For Each item As JObject In userData
            Dim runno As String = item("runno")
            Dim formulaid As String = item("formulaid")
            Dim batchsize As String = item("batchsize")
            Dim tbatch As String = item("tbatch")
            Dim batchlist As String = item("batchlist")

            DataGridView1.Rows.Add(runno, formulaid, batchsize, tbatch, batchlist)
        Next
    End Sub

    Private Sub DataGridView1_CellDoubleClick(sender As Object, e As DataGridViewCellEventArgs)
        If e.RowIndex >= 0 Then
            Me.Close()
            Dim row As DataGridViewRow = DataGridView1.Rows(e.RowIndex)
            Dim RunNo As Integer = row.Cells("RunNo").Value
            frmFetchWt.txtRunNo.Text = RunNo
        End If
    End Sub
End Class