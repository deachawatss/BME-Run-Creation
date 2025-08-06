<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class frmPreweighBatch
    Inherits System.Windows.Forms.Form

    'Form overrides dispose to clean up the component list.
    <System.Diagnostics.DebuggerNonUserCode()> _
    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        Try
            If disposing AndAlso components IsNot Nothing Then
                components.Dispose()
            End If
        Finally
            MyBase.Dispose(disposing)
        End Try
    End Sub

    'Required by the Windows Form Designer
    Private components As System.ComponentModel.IContainer

    'NOTE: The following procedure is required by the Windows Form Designer
    'It can be modified using the Windows Form Designer.  
    'Do not modify it using the code editor.
    <System.Diagnostics.DebuggerStepThrough()> _
    Private Sub InitializeComponent()
        Me.dgridBatchList = New System.Windows.Forms.DataGridView()
        Me.RunNo = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.Batch = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.FormulaID = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.BatchSize = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.runid = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.batchlist = New System.Windows.Forms.DataGridViewTextBoxColumn()
        CType(Me.dgridBatchList, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'dgridBatchList
        '
        Me.dgridBatchList.AllowUserToAddRows = False
        Me.dgridBatchList.AllowUserToDeleteRows = False
        Me.dgridBatchList.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.dgridBatchList.Columns.AddRange(New System.Windows.Forms.DataGridViewColumn() {Me.RunNo, Me.Batch, Me.FormulaID, Me.BatchSize, Me.runid, Me.batchlist})
        Me.dgridBatchList.Dock = System.Windows.Forms.DockStyle.Fill
        Me.dgridBatchList.Location = New System.Drawing.Point(0, 0)
        Me.dgridBatchList.Margin = New System.Windows.Forms.Padding(10, 9, 10, 9)
        Me.dgridBatchList.Name = "dgridBatchList"
        Me.dgridBatchList.ReadOnly = True
        Me.dgridBatchList.RowTemplate.Height = 30
        Me.dgridBatchList.Size = New System.Drawing.Size(1334, 728)
        Me.dgridBatchList.TabIndex = 3
        '
        'RunNo
        '
        Me.RunNo.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.RunNo.HeaderText = "Run No"
        Me.RunNo.Name = "RunNo"
        Me.RunNo.ReadOnly = True
        '
        'Batch
        '
        Me.Batch.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.Batch.HeaderText = "Batch No"
        Me.Batch.Name = "Batch"
        Me.Batch.ReadOnly = True
        '
        'FormulaID
        '
        Me.FormulaID.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.FormulaID.HeaderText = "Formula ID"
        Me.FormulaID.Name = "FormulaID"
        Me.FormulaID.ReadOnly = True
        '
        'BatchSize
        '
        Me.BatchSize.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.BatchSize.HeaderText = "Batch Size"
        Me.BatchSize.Name = "BatchSize"
        Me.BatchSize.ReadOnly = True
        '
        'runid
        '
        Me.runid.HeaderText = "runid"
        Me.runid.Name = "runid"
        Me.runid.ReadOnly = True
        Me.runid.Visible = False
        '
        'batchlist
        '
        Me.batchlist.HeaderText = "batchlist"
        Me.batchlist.Name = "batchlist"
        Me.batchlist.ReadOnly = True
        Me.batchlist.Visible = False
        '
        'frmPreweighBatch
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(19.0!, 37.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(1334, 728)
        Me.Controls.Add(Me.dgridBatchList)
        Me.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!)
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedToolWindow
        Me.Margin = New System.Windows.Forms.Padding(10, 9, 10, 9)
        Me.Name = "frmPreweighBatch"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "Batch List"
        CType(Me.dgridBatchList, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

    Friend WithEvents dgridBatchList As DataGridView
    Friend WithEvents RunNo As DataGridViewTextBoxColumn
    Friend WithEvents Batch As DataGridViewTextBoxColumn
    Friend WithEvents FormulaID As DataGridViewTextBoxColumn
    Friend WithEvents BatchSize As DataGridViewTextBoxColumn
    Friend WithEvents runid As DataGridViewTextBoxColumn
    Friend WithEvents batchlist As DataGridViewTextBoxColumn
End Class
