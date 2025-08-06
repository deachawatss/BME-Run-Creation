Imports Newtonsoft.Json.Linq

Public Class frmFetchWtBatchList

    Public myrunno As String = ""
    Public mybatchno As String = ""
    Private Sub frmFetchWtBatchList_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Dim myfilter = ""

        If myrunno <> "" Then
            myfilter = " where tbl_runhrd.runno = '" & myrunno & "'"
        End If

        If mybatchno <> "" Then
            myfilter &= If(myrunno <> "", " And ", "") & " tbl_rundata.batchno = '" & mybatchno & "'"
        End If

        Dim mdb As New MySQL()

        'Select Case
        '            tbl_runhrd.runno,
        '           tbl_runhrd.formulaid,
        '           tbl_runhrd.batchsize,
        '            count(*) as tbatch,
        '           group_concat(tbl_rundata.batchno) as batchlist
        '           from tbl_runhrd
        '           inner join tbl_rundata on tbl_rundata.runid = tbl_runhrd.runid
        '           group by tbl_runhrd.runno


        Dim query = "
            Select 
                    tbl_runhrd.runno,
                    tbl_runhrd.formulaid,
                    tbl_runhrd.batchsize,
                    tbl_rundata.batchno,
                    tbl_runhrd.runid
                    from tbl_runhrd
                    inner join tbl_rundata on tbl_rundata.runid = tbl_runhrd.runid
                    " & myfilter & "
                    
        "

        Dim userData = mdb.SelectData(query)

        DataGridView1.Rows.Clear()
        Dim dataTable As New DataTable()


        'While (userData.Read())

        For Each item As JObject In userData
            Dim runno As String = item("runno")
            Dim formulaid As String = item("formulaid")
            Dim batchsize As String = item("batchsize")
            Dim batchno As String = item("batchno")
            Dim runid As String = item("runid")

            DataGridView1.Rows.Add(runno, batchno, formulaid, batchsize, runid)
        Next

    End Sub

    Private Sub DataGridView1_CellDoubleClick(sender As Object, e As DataGridViewCellEventArgs) Handles DataGridView1.CellDoubleClick
        If e.RowIndex >= 0 Then
            Me.Close()
            Dim mdb As New MySQL()

            frmPartialPick.dgvpicked.Rows.Clear()
            frmPartialPick.dgpickedlist.Rows.Clear()
            frmPartialPick.dgvtopick.Rows.Clear()

            Dim row As DataGridViewRow = DataGridView1.Rows(e.RowIndex)
            Dim runno As String = row.Cells("RunNo").Value
            Dim batchno As String = row.Cells("Batch").Value
            Dim FormulaID As String = row.Cells("FormulaID").Value
            Dim BatchSize As String = row.Cells("BatchSize").Value
            'Dim Batch As String = row.Cells("Batch").Value
            Dim runid As String = row.Cells("runid").Value

            frmFetchWt.txtBatchNo.Text = batchno
            frmFetchWt.txtRunNo.Text = runno

            frmPartialPick.txtRunNo.Text = runno
            frmPartialPick.txtBatchno.Text = batchno


            Dim qry = String.Format("Select count(*) as qty from tbl_rundata where runid={0}", runid)
            Dim Batch = mdb.SelectDataScalar(qry)

            Dim lmydict As New Dictionary(Of String, String)
            lmydict.Add("RunNo", runno)
            lmydict.Add("FormulaID", FormulaID)
            lmydict.Add("BatchSize", BatchSize)
            lmydict.Add("Batch", Batch.Item("qty"))
            lmydict.Add("batchno", batchno)

            frmPartialPick.setmydatabatch(lmydict)
        End If
    End Sub

End Class