<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class frmLot
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
        Dim DataGridViewCellStyle16 As System.Windows.Forms.DataGridViewCellStyle = New System.Windows.Forms.DataGridViewCellStyle()
        Dim DataGridViewCellStyle17 As System.Windows.Forms.DataGridViewCellStyle = New System.Windows.Forms.DataGridViewCellStyle()
        Dim DataGridViewCellStyle18 As System.Windows.Forms.DataGridViewCellStyle = New System.Windows.Forms.DataGridViewCellStyle()
        Dim DataGridViewCellStyle19 As System.Windows.Forms.DataGridViewCellStyle = New System.Windows.Forms.DataGridViewCellStyle()
        Dim DataGridViewCellStyle20 As System.Windows.Forms.DataGridViewCellStyle = New System.Windows.Forms.DataGridViewCellStyle()
        Me.dglot = New System.Windows.Forms.DataGridView()
        Me.itemkey = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.LotNo = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.BinNo = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.qty = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.ExpiDate = New System.Windows.Forms.DataGridViewTextBoxColumn()
        CType(Me.dglot, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'dglot
        '
        Me.dglot.AllowUserToAddRows = False
        Me.dglot.AllowUserToDeleteRows = False
        Me.dglot.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.dglot.Columns.AddRange(New System.Windows.Forms.DataGridViewColumn() {Me.itemkey, Me.LotNo, Me.BinNo, Me.qty, Me.ExpiDate})
        Me.dglot.Dock = System.Windows.Forms.DockStyle.Fill
        Me.dglot.Location = New System.Drawing.Point(0, 0)
        Me.dglot.Margin = New System.Windows.Forms.Padding(23, 26, 23, 26)
        Me.dglot.Name = "dglot"
        Me.dglot.ReadOnly = True
        Me.dglot.RowHeadersWidth = 51
        Me.dglot.RowTemplate.Height = 30
        Me.dglot.Size = New System.Drawing.Size(1485, 700)
        Me.dglot.TabIndex = 1
        '
        'itemkey
        '
        Me.itemkey.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        DataGridViewCellStyle16.Alignment = System.Windows.Forms.DataGridViewContentAlignment.MiddleCenter
        Me.itemkey.DefaultCellStyle = DataGridViewCellStyle16
        Me.itemkey.HeaderText = "ITEM KEY"
        Me.itemkey.MinimumWidth = 6
        Me.itemkey.Name = "itemkey"
        Me.itemkey.ReadOnly = True
        '
        'LotNo
        '
        Me.LotNo.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        DataGridViewCellStyle17.Alignment = System.Windows.Forms.DataGridViewContentAlignment.MiddleCenter
        Me.LotNo.DefaultCellStyle = DataGridViewCellStyle17
        Me.LotNo.HeaderText = "LOT NO"
        Me.LotNo.MinimumWidth = 6
        Me.LotNo.Name = "LotNo"
        Me.LotNo.ReadOnly = True
        '
        'BinNo
        '
        Me.BinNo.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        DataGridViewCellStyle18.Alignment = System.Windows.Forms.DataGridViewContentAlignment.MiddleCenter
        Me.BinNo.DefaultCellStyle = DataGridViewCellStyle18
        Me.BinNo.HeaderText = "BIN NO"
        Me.BinNo.MinimumWidth = 6
        Me.BinNo.Name = "BinNo"
        Me.BinNo.ReadOnly = True
        '
        'qty
        '
        Me.qty.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        DataGridViewCellStyle19.Alignment = System.Windows.Forms.DataGridViewContentAlignment.MiddleCenter
        Me.qty.DefaultCellStyle = DataGridViewCellStyle19
        Me.qty.HeaderText = "QUANTITY"
        Me.qty.MinimumWidth = 6
        Me.qty.Name = "qty"
        Me.qty.ReadOnly = True
        '
        'ExpiDate
        '
        Me.ExpiDate.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        DataGridViewCellStyle20.Alignment = System.Windows.Forms.DataGridViewContentAlignment.MiddleCenter
        Me.ExpiDate.DefaultCellStyle = DataGridViewCellStyle20
        Me.ExpiDate.HeaderText = "EXPIRY"
        Me.ExpiDate.MinimumWidth = 6
        Me.ExpiDate.Name = "ExpiDate"
        Me.ExpiDate.ReadOnly = True
        '
        'frmLot
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(23.0!, 46.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(1485, 700)
        Me.Controls.Add(Me.dglot)
        Me.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!)
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedToolWindow
        Me.Margin = New System.Windows.Forms.Padding(9, 9, 9, 9)
        Me.Name = "frmLot"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "Find Lot"
        Me.TopMost = True
        CType(Me.dglot, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

    Friend WithEvents dglot As DataGridView
    Friend WithEvents itemkey As DataGridViewTextBoxColumn
    Friend WithEvents LotNo As DataGridViewTextBoxColumn
    Friend WithEvents BinNo As DataGridViewTextBoxColumn
    Friend WithEvents qty As DataGridViewTextBoxColumn
    Friend WithEvents ExpiDate As DataGridViewTextBoxColumn
End Class
