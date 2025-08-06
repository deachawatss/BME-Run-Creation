<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class frmFetchWtBatchList
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
        Me.DataGridView1 = New System.Windows.Forms.DataGridView()
        Me.RunNo = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.Batch = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.FormulaID = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.BatchSize = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.runid = New System.Windows.Forms.DataGridViewTextBoxColumn()
        CType(Me.DataGridView1, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'DataGridView1
        '
        Me.DataGridView1.AllowUserToAddRows = False
        Me.DataGridView1.AllowUserToDeleteRows = False
        Me.DataGridView1.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.DataGridView1.Columns.AddRange(New System.Windows.Forms.DataGridViewColumn() {Me.RunNo, Me.Batch, Me.FormulaID, Me.BatchSize, Me.runid})
        Me.DataGridView1.Dock = System.Windows.Forms.DockStyle.Fill
        Me.DataGridView1.Location = New System.Drawing.Point(0, 0)
        Me.DataGridView1.Margin = New System.Windows.Forms.Padding(10, 9, 10, 9)
        Me.DataGridView1.Name = "DataGridView1"
        Me.DataGridView1.ReadOnly = True
        Me.DataGridView1.RowTemplate.Height = 30
        Me.DataGridView1.Size = New System.Drawing.Size(1334, 728)
        Me.DataGridView1.TabIndex = 2
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
        'frmFetchWtBatchList
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(19.0!, 37.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(1334, 728)
        Me.Controls.Add(Me.DataGridView1)
        Me.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedToolWindow
        Me.Margin = New System.Windows.Forms.Padding(6, 6, 6, 6)
        Me.Name = "frmFetchWtBatchList"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "Batch List"
        CType(Me.DataGridView1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

    Friend WithEvents DataGridView1 As DataGridView
    Friend WithEvents RunNo As DataGridViewTextBoxColumn
    Friend WithEvents Batch As DataGridViewTextBoxColumn
    Friend WithEvents FormulaID As DataGridViewTextBoxColumn
    Friend WithEvents BatchSize As DataGridViewTextBoxColumn
    Friend WithEvents runid As DataGridViewTextBoxColumn
End Class
